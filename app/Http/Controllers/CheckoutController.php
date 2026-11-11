<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;

class CheckoutController extends Controller
{
    private const ARTWORK_UNAVAILABLE = 'artwork-unavailable';

    public function index(Request $request, CartController $cartController): View|RedirectResponse
    {
        $orderItems = $cartController->items($request);

        if ($orderItems === []) {
            return redirect()->route('cart')
                ->with('status', 'Add a published artwork before checking out.');
        }

        return view('checkout', [
            'orderItems' => $orderItems,
            'total' => array_sum(array_map(
                fn (array $item): float => $item['price'] * $item['quantity'],
                $orderItems,
            )),
            'userEmail' => $request->user()->email,
            'userInitials' => collect(preg_split('/\s+/', trim($request->user()->name)))
                ->filter()
                ->take(2)
                ->map(fn (string $part): string => strtoupper(substr($part, 0, 1)))
                ->implode(''),
        ]);
    }

    public function store(Request $request, CartController $cartController): RedirectResponse
    {
        $paymentMethod = mb_strtolower(trim((string) $request->input('payment_method', 'card'))) === 'gcash' ? 'gcash' : 'card';
        $request->merge([
            'payment_method' => $paymentMethod,
            'card_number' => preg_replace('/\D/', '', (string) $request->input('card_number')),
            'gcash_number' => preg_replace('/\D/', '', (string) $request->input('gcash_number')),
        ]);

        $data = $request->validate([
            'contact_email' => ['required', 'email', 'max:255'],
            'payment_method' => ['required', Rule::in(['card', 'gcash'])],
            'cardholder_name' => ['exclude_unless:payment_method,card', 'required', 'string', 'max:255'],
            'card_number' => ['exclude_unless:payment_method,card', 'required', 'digits_between:13,19'],
            'card_expiry' => ['exclude_unless:payment_method,card', 'required', 'regex:/^(0[1-9]|1[0-2])\s*\/\s*\d{2}$/'],
            'card_cvc' => ['exclude_unless:payment_method,card', 'required', 'digits_between:3,4'],
            'gcash_name' => ['exclude_unless:payment_method,gcash', 'required', 'string', 'max:255'],
            'gcash_number' => ['exclude_unless:payment_method,gcash', 'required', 'regex:/^09\d{9}$/'],
            'gcash_reference' => ['exclude_unless:payment_method,gcash', 'required', 'string', 'alpha_num', 'min:6', 'max:30'],
        ], [
            'cardholder_name.required' => 'Enter the name shown on the card.',
            'card_number.required' => 'Enter your card number.',
            'card_number.digits_between' => 'Card numbers have 13 to 19 digits.',
            'card_expiry.required' => 'Enter the card expiry date.',
            'card_expiry.regex' => 'Use the MM / YY format for the expiry date.',
            'card_cvc.required' => 'Enter the security code.',
            'card_cvc.digits_between' => 'The security code has 3 or 4 digits.',
            'gcash_name.required' => 'Enter the name on your GCash account.',
            'gcash_number.required' => 'Enter your GCash mobile number.',
            'gcash_number.regex' => 'Use an 11-digit mobile number that starts with 09.',
            'gcash_reference.required' => 'Enter the GCash reference number from your payment receipt.',
            'gcash_reference.alpha_num' => 'The reference number can only contain letters and numbers.',
        ]);
        $orderItems = $cartController->items($request);

        if ($orderItems === []) {
            return redirect()->route('cart')
                ->with('status', 'Your cart is empty or no longer contains available artworks.');
        }

        $isGcash = $paymentMethod === 'gcash';

        try {
            $order = DB::transaction(function () use ($request, $data, $orderItems, $isGcash): Order {
                $artworkIds = array_column($orderItems, 'id');

                $reservedCount = Artwork::query()
                    ->whereIn('id', $artworkIds)
                    ->availableForPurchase()
                    ->update(['availability' => Artwork::AVAILABILITY_RESERVED]);

                if ($reservedCount !== count($artworkIds)) {
                    throw new RuntimeException(self::ARTWORK_UNAVAILABLE);
                }

                $total = array_sum(array_map(
                    fn (array $item): float => $item['price'] * $item['quantity'],
                    $orderItems,
                ));

                $order = Order::query()->create([
                    'order_number' => 'pending-'.Str::uuid(),
                    'user_id' => $request->user()->id,
                    'customer_name' => $request->user()->name,
                    'customer_email' => $data['contact_email'],
                    'total' => $total,
                    'status' => Order::STATUS_PENDING,
                    'payment_method' => $isGcash ? 'GCash' : 'Card',
                    'payment_reference' => $isGcash
                        ? strtoupper($data['gcash_reference'])
                        : 'Card ending '.substr($data['card_number'], -4),
                    'payment_account_name' => $isGcash ? $data['gcash_name'] : $data['cardholder_name'],
                ]);
                $order->update(['order_number' => 'ART-'.str_pad((string) $order->id, 6, '0', STR_PAD_LEFT)]);

                foreach ($orderItems as $item) {
                    OrderItem::query()->create([
                        'order_id' => $order->id,
                        'artwork_id' => $item['id'],
                        'title' => $item['title'],
                        'artist_name' => $item['author'],
                        'image_path' => $item['image_path'],
                        'unit_price' => $item['price'],
                        'quantity' => $item['quantity'],
                    ]);
                }

                return $order;
            });
        } catch (RuntimeException $exception) {
            if ($exception->getMessage() !== self::ARTWORK_UNAVAILABLE) {
                throw $exception;
            }

            $request->session()->put('cart', array_fill_keys(
                Artwork::query()->whereIn('id', array_column($orderItems, 'id'))->availableForPurchase()->pluck('id')->all(),
                1,
            ));

            return redirect()->route('cart')
                ->with('status', 'Sorry, one or more artworks in your cart were just purchased by another collector. We removed them from your cart.');
        }

        $request->session()->forget('cart');

        return redirect()->route('order.success', $order);
    }
}
