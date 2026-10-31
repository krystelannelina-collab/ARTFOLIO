<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $orders = $this->filteredOrders($filters)
            ->with('items')
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'filters' => $filters,
            'stats' => $this->stats(),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load('items'),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $this->validatedFilters($request);
        $orders = $this->filteredOrders($filters)->with('items')->latest()->get();
        $filename = 'artfolio-orders-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($orders): void {
            $stream = fopen('php://output', 'w');
            if ($stream === false) {
                throw new \RuntimeException('The orders export stream could not be opened.');
            }

            $writeCsvRow = static function (array $row) use ($stream): void {
                if (fputcsv($stream, $row, ',', '"', '') === false) {
                    throw new \RuntimeException('The orders export could not be written.');
                }
            };

            $writeCsvRow(['Order ID', 'Customer', 'Email', 'Date', 'Payment status', 'Payment method', 'Total', 'Artworks']);

            foreach ($orders as $order) {
                $writeCsvRow([
                    $this->spreadsheetSafe($order->order_number),
                    $this->spreadsheetSafe($order->customer_name),
                    $this->spreadsheetSafe($order->customer_email),
                    $order->created_at->format('Y-m-d H:i:s'),
                    $this->spreadsheetSafe($order->status),
                    $this->spreadsheetSafe($order->payment_method === 'not_configured' ? 'Not configured' : $order->payment_method),
                    number_format((float) $order->total, 2, '.', ''),
                    $this->spreadsheetSafe($order->items->map(
                        fn ($item): string => "{$item->title} x{$item->quantity}",
                    )->implode('; ')),
                ]);
            }

            fclose($stream);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return array{search?: string, status?: string, date?: string, sort?: string}
     */
    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['pending', 'completed', 'refunded', 'failed'])],
            'date' => ['nullable', Rule::in(['all', '7', '30', '365'])],
            'sort' => ['nullable', Rule::in(['newest', 'oldest'])],
        ]);
    }

    /**
     * @param  array{search?: string, status?: string, date?: string, sort?: string}  $filters
     * @return Builder<Order>
     */
    private function filteredOrders(array $filters): Builder
    {
        $query = Order::query();
        $search = trim($filters['search'] ?? '');

        if ($search !== '') {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhereHas('items', fn (Builder $items): Builder => $items->where('title', 'like', "%{$search}%"));
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $dateRange = $filters['date'] ?? 'all';
        if ($dateRange !== 'all') {
            $query->where('created_at', '>=', now()->subDays((int) $dateRange)->startOfDay());
        }

        return $query->orderBy('created_at', ($filters['sort'] ?? 'newest') === 'oldest' ? 'asc' : 'desc');
    }

    /**
     * @return array{revenue: float, completed: int, pending: int, refunded: int, failed: int}
     */
    private function stats(): array
    {
        return [
            'revenue' => (float) Order::query()->where('status', 'completed')->sum('total'),
            'completed' => Order::query()->where('status', 'completed')->count(),
            'pending' => Order::query()->where('status', 'pending')->count(),
            'refunded' => Order::query()->where('status', 'refunded')->count(),
            'failed' => Order::query()->where('status', 'failed')->count(),
        ];
    }

    private function spreadsheetSafe(string $value): string
    {
        return preg_match('/^[\s]*[=+\-@]/', $value) === 1 ? "'{$value}" : $value;
    }
}
