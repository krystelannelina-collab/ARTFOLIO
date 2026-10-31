<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
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
        $data = $request->validate([
            'contact_email' => ['required', 'email', 'max:255'],
        ]);
        $orderItems = $cartController->items($request);

        if ($orderItems === []) {
            return redirect()->route('cart')
                ->with('status', 'Your cart is empty or no longer contains available artworks.');
        }

        $order = DB::transaction(function () use ($request, $data, $orderItems): Order {
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
                'status' => 'pending',
                'payment_method' => 'not_configured',
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

        $request->session()->forget('cart');

        return redirect()->route('order.success', $order);
    }
}
