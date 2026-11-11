<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'visibility' => ['nullable', Rule::in(['all', 'visible', 'hidden'])],
            'sort' => ['nullable', Rule::in(['name_asc', 'name_desc', 'most_artworks'])],
        ]);
        $search = trim($filters['search'] ?? '');
        $query = Category::query()
            ->leftJoinSub(
                Artwork::query()
                    ->select('category')
                    ->selectRaw('COUNT(*) as artwork_count')
                    ->selectRaw('SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as published_count', ['published'])
                    ->groupBy('category'),
                'category_artwork_counts',
                'categories.name',
                '=',
                'category_artwork_counts.category',
            )
            ->select('categories.*')
            ->selectRaw('COALESCE(category_artwork_counts.artwork_count, 0) as artwork_count')
            ->selectRaw('COALESCE(category_artwork_counts.published_count, 0) as published_count');

        if ($search !== '') {
            $query->where('categories.name', 'like', "%{$search}%");
        }

        if (($filters['visibility'] ?? 'all') !== 'all') {
            $query->where('categories.is_visible', ($filters['visibility'] ?? '') === 'visible');
        }

        $sort = $filters['sort'] ?? 'name_asc';
        match ($sort) {
            'name_desc' => $query->orderByDesc('categories.name'),
            'most_artworks' => $query->orderByDesc('artwork_count')->orderBy('categories.name'),
            default => $query->orderBy('categories.name'),
        };

        $editCategory = new Category(['is_visible' => true]);
        if (old('_form_context') === 'edit-category' && old('_category_id')) {
            $editCategory = Category::query()->find(old('_category_id')) ?? $editCategory;
        }

        $knownCategoryNames = Category::query()->select('name');

        return view('admin.categories.index', [
            'categories' => $query->paginate(8)->withQueryString(),
            'filters' => $filters,
            'sort' => $sort,
            'newCategory' => new Category(['is_visible' => true]),
            'editCategory' => $editCategory,
            'editModalOpen' => old('_form_context') === 'edit-category' && $editCategory->exists,
            'stats' => [
                'total' => Category::query()->count(),
                'visible' => Category::query()->where('is_visible', true)->count(),
                'publishedArtworks' => Artwork::query()->where('status', 'published')->count(),
                'uncategorized' => Artwork::query()
                    ->where(function (Builder $query) use ($knownCategoryNames): void {
                        $query->whereNull('category')
                            ->orWhere('category', '')
                            ->orWhere('category', 'Uncategorized')
                            ->orWhereNotIn('category', $knownCategoryNames);
                    })
                    ->count(),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedCategory($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['is_visible'] = true;
        $data['sort_order'] = (int) Category::query()->max('sort_order') + 1;
        Category::query()->create($data);

        return redirect()->route('admin.categories.index')
            ->with('status', 'Category added to your catalog.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validatedCategory($request, $category);
        $data['slug'] = $this->uniqueSlug($data['name'], $category);

        DB::transaction(function () use ($category, $data): void {
            if ($category->name !== $data['name']) {
                Artwork::query()->where('category', $category->name)->update(['category' => $data['name']]);
            }

            $category->update($data);
        });

        return redirect()->route('admin.categories.index')
            ->with('status', 'Category updated. Artwork assignments were preserved.');
    }

    public function toggleVisibility(Category $category): RedirectResponse
    {
        $category->update(['is_visible' => ! $category->is_visible]);

        return redirect()->route('admin.categories.index')
            ->with('status', $category->is_visible
                ? 'Category is now visible in the storefront.'
                : 'Category hidden from storefront navigation. Artwork assignments were kept.');
    }

    private function validatedCategory(Request $request, ?Category $category = null): array
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'description' => trim((string) $request->input('description')),
        ]);
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                function (string $attribute, mixed $value, \Closure $fail) use ($category): void {
                    $duplicateExists = Category::query()
                        ->whereRaw('LOWER(name) = ?', [mb_strtolower((string) $value)])
                        ->when($category !== null, fn (Builder $query): Builder => $query->where('id', '!=', $category->id))
                        ->exists();

                    if ($duplicateExists) {
                        $fail('A category with this name already exists.');
                    }
                },
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $data['description'] = $data['description'] ?? '';

        return $data;
    }

    private function uniqueSlug(string $name, ?Category $category = null): string
    {
        $baseSlug = Str::slug($name) ?: 'category';
        $slug = $baseSlug;
        $suffix = 2;

        while (Category::query()
            ->where('slug', $slug)
            ->when($category !== null, fn (Builder $query): Builder => $query->where('id', '!=', $category->getKey()))
            ->exists()) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
