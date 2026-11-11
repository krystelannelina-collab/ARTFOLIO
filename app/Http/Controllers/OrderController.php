<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with('items')
            ->latest()
            ->paginate(8);

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        return view('order-success', [
            'order' => $order->load('items'),
        ]);
    }

    /**
     * Serve the original full-resolution file to the buyer of an approved order.
     */
    public function downloadItem(Request $request, Order $order, OrderItem $item): BinaryFileResponse|StreamedResponse
    {
        abort_unless($order->user_id === $request->user()->id, 404);
        abort_unless($item->order_id === $order->id, 404);
        abort_unless($order->isPaid(), 403, 'Downloads unlock after your payment is approved.');

        $titleSlug = Str::slug($item->title ?: 'artwork');
        $originalPath = $item->artwork?->original_path;

        if ($originalPath !== null && Storage::disk('local')->exists($originalPath)) {
            $extension = pathinfo($originalPath, PATHINFO_EXTENSION) ?: 'png';

            return Storage::disk('local')->download($originalPath, "{$titleSlug}.{$extension}");
        }

        $filePath = public_path($item->image_path);
        abort_unless(is_file($filePath), 404);

        $extension = pathinfo($filePath, PATHINFO_EXTENSION) ?: 'png';

        return response()->download($filePath, "{$titleSlug}.{$extension}");
    }

    public function downloadReceipt(Request $request, Order $order): StreamedResponse
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        $order->loadMissing('items');
        $filename = 'receipt-'.Str::slug($order->order_number).'.txt';

        return response()->streamDownload(function () use ($order): void {
            $lines = [
                '==================================================',
                '                 ARTFOLIO RECEIPT                 ',
                '==================================================',
                "Order Number   : #{$order->order_number}",
                'Date           : '.$order->created_at->format('F j, Y g:i A'),
                "Customer       : {$order->customer_name} ({$order->customer_email})",
                'Payment Status : '.$order->statusLabel(),
                'Payment Method : '.($order->payment_method === 'not_configured' ? 'Not configured' : $order->payment_method),
                '--------------------------------------------------',
                'Items:',
            ];

            foreach ($order->items as $item) {
                $lineTotal = number_format((float) $item->unit_price * $item->quantity, 2);
                $lines[] = " - {$item->title} (by {$item->artist_name}) x{$item->quantity}: ₱{$lineTotal}";
            }

            $lines[] = '--------------------------------------------------';
            $lines[] = ($order->isPaid() ? 'Total Paid     : ' : 'Order Total    : ').'₱'.number_format((float) $order->total, 2);
            $lines[] = '==================================================';

            echo implode(PHP_EOL, $lines).PHP_EOL;
        }, $filename, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
