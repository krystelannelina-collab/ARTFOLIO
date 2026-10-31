<?php

namespace App\Providers;

use App\Models\Category;
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
        View::composer('partials.sidebar', function (ViewInstance $view): void {
            $view->with('storeCategories', Category::query()
                ->where('is_visible', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['name'])
                ->map(fn (Category $category): array => [
                    'label' => $category->name,
                    'url' => url('/artworks?category='.urlencode($category->name)),
                    'type' => match (strtolower($category->name)) {
                        'typography' => 'typography',
                        'pixel' => 'pixel',
                        'poster' => 'poster',
                        'digital' => 'digital',
                        default => 'abstract',
                    },
                ])
                ->all());
        });
    }
}
