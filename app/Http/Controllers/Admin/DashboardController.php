<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\Feedback;
use App\Models\Order;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $chartStart = CarbonImmutable::now()->startOfMonth()->subMonths(5);
        $categoryCounts = Artwork::query()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('total', 'category');
        $monthlyClientCounts = User::query()
            ->where('role', 'client')
            ->where('created_at', '>=', $chartStart)
            ->get(['created_at'])
            ->countBy(fn (User $user): string => $user->created_at->format('Y-m'));
        $monthLabels = collect(range(0, 5))
            ->map(fn (int $monthsAgo): CarbonImmutable => $chartStart->addMonths($monthsAgo));

        return view('admin.dashboard', [
            'stats' => [
                'artworks' => Artwork::query()->count(),
                'artists' => Artwork::query()->distinct('artist_name')->count('artist_name'),
                'clients' => User::query()->where('role', 'client')->count(),
                'categories' => $categoryCounts->count(),
                'pendingPayments' => Order::query()->where('status', Order::STATUS_PENDING)->count(),
            ],
            'recentFeedback' => Feedback::query()->latest()->limit(5)->get(),
            'categoryChart' => [
                'labels' => $categoryCounts->keys()->values(),
                'values' => $categoryCounts->values(),
            ],
            'clientSignupChart' => [
                'labels' => $monthLabels->map(fn (CarbonImmutable $month): string => $month->format('M')),
                'values' => $monthLabels->map(fn (CarbonImmutable $month): int => (int) $monthlyClientCounts->get($month->format('Y-m'), 0)),
            ],
            'recentArtworks' => Artwork::query()->latest()->limit(5)->get(),
            'recentClients' => User::query()
                ->where('role', 'client')
                ->latest()
                ->limit(5)
                ->get(['id', 'name', 'email', 'created_at']),
        ]);
    }
}
