<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class AdminCustomersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_customer_metrics_and_empty_state(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.customers.index'))
            ->assertOk()
            ->assertSee('Customers')
            ->assertSee('No customers yet')
            ->assertViewHas('stats', [
                'totalCustomers' => 0,
                'customersWithOrders' => 0,
                'ordersPlaced' => 0,
                'paidSales' => 0.0,
            ]);
    }

    public function test_admin_can_search_filter_and_sort_customers_by_order_activity(): void
    {
        $firstCustomer = User::factory()->create([
            'name' => 'Alex Reyes',
            'email' => 'alex@example.com',
            'role' => 'client',
            'created_at' => now()->subDays(5),
        ]);
        $secondCustomer = User::factory()->create([
            'name' => 'Jamie Cruz',
            'email' => 'jamie@example.com',
            'role' => 'client',
            'created_at' => now()->subDay(),
        ]);
        $admin = User::factory()->create(['role' => 'admin']);
        $this->createOrder($firstCustomer, 'completed', 70);
        $this->createOrder($firstCustomer, 'pending', 25);
        $this->createOrder($secondCustomer, 'refunded', 12);

        $this->actingAs($admin)
            ->get(route('admin.customers.index', [
                'search' => 'alex@',
                'status' => 'ordered',
                'sort' => 'most_orders',
            ]))
            ->assertOk()
            ->assertSee('Alex Reyes')
            ->assertDontSee('Jamie Cruz')
            ->assertSee('₱70.00')
            ->assertViewHas('stats', [
                'totalCustomers' => 2,
                'customersWithOrders' => 2,
                'ordersPlaced' => 3,
                'paidSales' => 70.0,
            ]);

        $this->get(route('admin.customers.index', ['status' => 'no_orders']))
            ->assertOk()
            ->assertSee('No matching customers');

        $this->deleteOrdersForCustomer($firstCustomer);
        $this->get(route('admin.customers.index', ['sort' => 'oldest']))
            ->assertOk()
            ->assertViewHas('customers', fn (LengthAwarePaginator $customers): bool => $customers
                ->pluck('email')->all() === [$firstCustomer->email, $secondCustomer->email]);
    }

    public function test_admin_can_filter_customers_without_orders_and_open_their_orders(): void
    {
        $customer = User::factory()->create([
            'name' => 'Casey Santos',
            'email' => 'casey@example.com',
            'role' => 'client',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.customers.index', ['status' => 'no_orders']))
            ->assertOk()
            ->assertSee('Casey Santos')
            ->assertSee(route('admin.orders.index', ['search' => $customer->name]), false);
    }

    public function test_non_admins_cannot_access_customer_directory(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'client']))
            ->get(route('admin.customers.index'))
            ->assertForbidden();

        auth()->logout();

        $this->get(route('admin.customers.index'))
            ->assertRedirect(route('login'));
    }

    private function createOrder(User $customer, string $status, float $total): Order
    {
        static $orderNumber = 1;

        return Order::query()->create([
            'order_number' => 'CUST-'.str_pad((string) $orderNumber++, 6, '0', STR_PAD_LEFT),
            'user_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'total' => $total,
            'status' => $status,
            'payment_method' => 'not_configured',
        ]);
    }

    private function deleteOrdersForCustomer(User $customer): void
    {
        Order::query()->where('user_id', $customer->id)->delete();
    }
}
