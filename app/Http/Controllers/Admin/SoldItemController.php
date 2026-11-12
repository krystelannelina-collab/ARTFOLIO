<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SoldItemController extends Controller
{
    /**
     * The date an item counts as sold: when its payment was approved, or the
     * order date for older orders approved before review dates were recorded.
     */
    private const SOLD_AT = 'COALESCE(orders.payment_reviewed_at, orders.created_at)';

    /**
     * List every artwork sold through an approved (completed) order.
     */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'period' => ['nullable', Rule::in(['all', 'last_30_days', 'this_month', 'this_year'])],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'highest_price', 'title'])],
        ]);
        $search = trim($filters['search'] ?? '');
        $period = $filters['period'] ?? 'all';
        $sort = $filters['sort'] ?? 'newest';

        $soldItemsQuery = $this->soldItemsQuery()
            ->select('order_items.*')
            ->addSelect('orders.order_number', 'orders.customer_name', 'orders.customer_email')
            ->selectRaw(self::SOLD_AT.' as sold_at')
            ->with('artwork:id,slug,category');

        if ($search !== '') {
            $soldItemsQuery->where(function (Builder $query) use ($search): void {
                $query->where('order_items.title', 'like', "%{$search}%")
                    ->orWhere('order_items.artist_name', 'like', "%{$search}%")
                    ->orWhere('orders.customer_name', 'like', "%{$search}%")
                    ->orWhere('orders.customer_email', 'like', "%{$search}%")
                    ->orWhere('orders.order_number', 'like', "%{$search}%");
            });
        }

        $periodStart = match ($period) {
            'last_30_days' => now()->subDays(30),
            'this_month' => now()->startOfMonth(),
            'this_year' => now()->startOfYear(),
            default => null,
        };

        if ($periodStart !== null) {
            $soldItemsQuery->whereRaw(self::SOLD_AT.' >= ?', [$periodStart]);
        }

        match ($sort) {
            'oldest' => $soldItemsQuery->orderByRaw(self::SOLD_AT.' asc')->orderBy('order_items.id'),
            'highest_price' => $soldItemsQuery->orderByDesc('order_items.subtotal')->orderBy('order_items.title'),
            'title' => $soldItemsQuery->orderBy('order_items.title'),
            default => $soldItemsQuery->orderByRaw(self::SOLD_AT.' desc')->orderByDesc('order_items.id'),
        };

        return view('admin.sold.index', [
            'soldItems' => $soldItemsQuery->paginate(10)->withQueryString(),
            'filters' => $filters,
            'period' => $period,
            'sort' => $sort,
            'hasFilters' => $search !== '' || $period !== 'all',
            'stats' => $this->stats(),
        ]);
    }

    /**
     * Order items that belong to approved orders.
     *
     * @return Builder<OrderItem>
     */
    private function soldItemsQuery(): Builder
    {
        return OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', Order::STATUS_COMPLETED);
    }

    /**
     * Headline numbers for the page, always across every sale (filters do not apply).
     *
     * @return array{itemsSold: int, salesTotal: float, averagePrice: float, soldLast30Days: int, topArtist: ?string, topArtistSold: int}
     */
    private function stats(): array
    {
        $itemsSold = (int) $this->soldItemsQuery()->sum('order_items.quantity');
        $salesTotal = (float) $this->soldItemsQuery()->sum('order_items.subtotal');
        $topArtist = $this->soldItemsQuery()
            ->select('order_items.artist_name')
            ->selectRaw('SUM(order_items.quantity) as artist_sold')
            ->groupBy('order_items.artist_name')
            ->orderByDesc('artist_sold')
            ->orderBy('order_items.artist_name')
            ->toBase()
            ->first();

        return [
            'itemsSold' => $itemsSold,
            'salesTotal' => $salesTotal,
            'averagePrice' => $itemsSold > 0 ? round($salesTotal / $itemsSold, 2) : 0.0,
            'soldLast30Days' => (int) $this->soldItemsQuery()
                ->whereRaw(self::SOLD_AT.' >= ?', [now()->subDays(30)])
                ->sum('order_items.quantity'),
            'topArtist' => $topArtist?->artist_name,
            'topArtistSold' => (int) ($topArtist->artist_sold ?? 0),
        ];
    }
}
