<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('peso', fn (string $amount): string => "<?php echo e(\\App\\Providers\\AppServiceProvider::formatPeso({$amount})); ?>");

        View::composer('partials.account-menu', function (ViewInstance $view): void {
            $user = auth()->user();

            $view->with([
                'menuUser' => $user,
                'orderUpdates' => $user?->orderUpdates()->limit(5)->get() ?? collect(),
                'unseenOrderUpdates' => $user?->unseenOrderUpdatesCount() ?? 0,
            ]);
        });

        View::composer('partials.site-header', function (ViewInstance $view): void {
            $view->with('storeCategories', Category::query()
                ->where('is_visible', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['name'])
                ->map(fn (Category $category): array => [
                    'label' => $category->name,
                    'url' => url('/artworks?category='.urlencode($category->name)),
                ])
                ->all());
        });
    }

    /**
     * Format an amount as Philippine pesos, such as "₱1,250.00". Prices are
     * stored in pesos, so the value is never converted.
     */
    public static function formatPeso(float|int|string|null $amount): string
    {
        return '₱'.number_format((float) $amount, 2);
    }
}
