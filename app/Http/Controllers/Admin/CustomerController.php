<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['all', 'ordered', 'pending', 'no_orders'])],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'most_orders', 'highest_spend', 'name'])],
        ]);
        $search = trim($filters['search'] ?? '');
        $sort = $filters['sort'] ?? 'newest';
        $clientUserIds = User::query()->where('role', 'client')->select('id');
        $orderTotals = Order::query()
            ->select('user_id')
            ->selectRaw('COUNT(*) as orders_count')
            ->selectRaw('SUM(CASE WHEN status = ? THEN total ELSE 0 END) as paid_total', [Order::STATUS_COMPLETED])
            ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending_count', [Order::STATUS_PENDING])
            ->selectRaw('MAX(created_at) as last_order_at')
            ->whereNotNull('user_id')
            ->groupBy('user_id');
        $customersQuery = User::query()
            ->where('users.role', 'client')
            ->leftJoinSub($orderTotals, 'customer_order_totals', 'users.id', '=', 'customer_order_totals.user_id')
            ->select('users.*')
            ->selectRaw('COALESCE(customer_order_totals.orders_count, 0) as orders_count')
            ->selectRaw('COALESCE(customer_order_totals.paid_total, 0) as paid_total')
            ->selectRaw('COALESCE(customer_order_totals.pending_count, 0) as pending_count')
            ->selectRaw('customer_order_totals.last_order_at as last_order_at');

        if ($search !== '') {
            $customersQuery->where(function (Builder $query) use ($search): void {
                $query->where('users.name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%")
                    ->orWhere('users.username', 'like', "%{$search}%");
            });
        }

        match ($filters['status'] ?? 'all') {
            'ordered' => $customersQuery->whereRaw('COALESCE(customer_order_totals.orders_count, 0) > 0'),
            'pending' => $customersQuery->whereRaw('COALESCE(customer_order_totals.pending_count, 0) > 0'),
            'no_orders' => $customersQuery->whereRaw('COALESCE(customer_order_totals.orders_count, 0) = 0'),
            default => null,
        };

        match ($sort) {
            'oldest' => $customersQuery->orderBy('users.created_at')->orderBy('users.id'),
            'most_orders' => $customersQuery->orderByDesc('orders_count')->orderBy('users.name'),
            'highest_spend' => $customersQuery->orderByDesc('paid_total')->orderBy('users.name'),
            'name' => $customersQuery->orderBy('users.name'),
            default => $customersQuery->orderByDesc('users.created_at')->orderByDesc('users.id'),
        };

        $totalCustomers = User::query()->where('role', 'client')->count();
        $customersWithOrders = Order::query()->whereIn('user_id', $clientUserIds)->distinct()->count('user_id');
        $payingCustomers = Order::query()
            ->whereIn('user_id', $clientUserIds)
            ->where('status', Order::STATUS_COMPLETED)
            ->distinct()
            ->count('user_id');
        $paidSales = (float) Order::query()
            ->whereIn('user_id', $clientUserIds)
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('total');

        return view('admin.customers.index', [
            'customers' => $customersQuery->paginate(8)->withQueryString(),
            'filters' => $filters,
            'sort' => $sort,
            'hasFilters' => $search !== '' || ($filters['status'] ?? 'all') !== 'all',
            'stats' => [
                'totalCustomers' => $totalCustomers,
                'newCustomers' => User::query()
                    ->where('role', 'client')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->count(),
                'customersWithOrders' => $customersWithOrders,
                'orderingShare' => $totalCustomers > 0 ? (int) round($customersWithOrders / $totalCustomers * 100) : 0,
                'ordersPlaced' => Order::query()->whereIn('user_id', $clientUserIds)->count(),
                'pendingOrders' => Order::query()
                    ->whereIn('user_id', $clientUserIds)
                    ->where('status', Order::STATUS_PENDING)
                    ->count(),
                'paidSales' => $paidSales,
                'averagePerPayingCustomer' => $payingCustomers > 0 ? $paidSales / $payingCustomers : 0.0,
            ],
        ]);
    }
}
