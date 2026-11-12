<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories | ARTFOLIO Admin</title>
    <meta name="description" content="Organize artwork categories and storefront visibility in ARTFOLIO.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-categories.css') }}">
</head>
<body>
<div class="admin-layout">
    @include('admin.partials.sidebar', ['active' => 'categories'])

    <main class="admin-main">
        <header class="admin-page-heading categories-page-heading">
            <div>
                <span class="admin-eyebrow">STORE MANAGEMENT</span>
                <h1>Categories</h1>
                <p>Organize your artwork and help customers discover their style.</p>
            </div>
            <button type="button" class="category-primary-button" id="open-category-modal">
                <span aria-hidden="true">+</span>
                Add category
            </button>
        </header>

        @if (session('status'))
            <div class="category-notice" role="status">{{ session('status') }}</div>
        @endif

        <section class="categories-overview" aria-label="Category and artwork totals">
            <article>
                <span>Total categories</span>
                <strong>{{ number_format($stats['total']) }}</strong>
                <small>Artwork styles in your catalog</small>
            </article>
            <article>
                <span>Visible categories</span>
                <strong>{{ number_format($stats['visible']) }}</strong>
                <small>Available in storefront navigation</small>
            </article>
            <article>
                <span>Published artworks</span>
                <strong>{{ number_format($stats['publishedArtworks']) }}</strong>
                <small>Across all categories</small>
            </article>
            <article>
                <span>Uncategorized</span>
                <strong>{{ number_format($stats['uncategorized']) }}</strong>
                <small>Artworks needing a category</small>
            </article>
        </section>

        <section class="admin-panel categories-table-panel" aria-label="Category directory">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="categories-filter-bar">
                <label class="categories-search">
                    <span class="categories-visually-hidden">Search category names</span>
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m16 16 4 4"></path>
                    </svg>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search category name...">
                </label>

                <label class="categories-filter">
                    <span class="categories-visually-hidden">Filter by visibility</span>
                    <select name="visibility">
                        <option value="all" @selected(($filters['visibility'] ?? 'all') === 'all')>All visibility</option>
                        <option value="visible" @selected(($filters['visibility'] ?? '') === 'visible')>Visible</option>
                        <option value="hidden" @selected(($filters['visibility'] ?? '') === 'hidden')>Hidden</option>
                    </select>
                </label>

                <label class="categories-filter categories-sort">
                    <span class="categories-visually-hidden">Sort categories</span>
                    <select name="sort">
                        <option value="name_asc" @selected($sort === 'name_asc')>Name: A–Z</option>
                        <option value="name_desc" @selected($sort === 'name_desc')>Name: Z–A</option>
                        <option value="most_artworks" @selected($sort === 'most_artworks')>Most artworks</option>
                    </select>
                </label>

                <button type="submit" class="categories-filter-submit">Apply</button>
                <a href="{{ route('admin.categories.index') }}" class="categories-filter-reset">Reset</a>
            </form>

            @if ($categories->isNotEmpty())
                <div class="categories-table-scroll">
                    <table class="categories-table">
                        <thead>
                            <tr>
                                <th scope="col">ORDER</th>
                                <th scope="col">CATEGORY</th>
                                <th scope="col">ARTWORKS</th>
                                <th scope="col">PUBLISHED</th>
                                <th scope="col">VISIBILITY</th>
                                <th scope="col">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr class="{{ $category->is_visible ? '' : 'category-row-hidden' }}">
                                    <td class="category-order">{{ str_pad((string) ($categories->firstItem() + $loop->index), 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <span class="category-name-cell">
                                            <strong>{{ $category->name }}</strong>
                                            <small>{{ $category->description ?: 'No category description yet.' }}</small>
                                        </span>
                                    </td>
                                    <td class="category-count">{{ number_format((int) $category->artwork_count) }}</td>
                                    <td>{{ number_format((int) $category->published_count) }}</td>
                                    <td>
                                        <span class="category-visibility {{ $category->is_visible ? 'is-visible' : 'is-hidden' }}">
                                            {{ $category->is_visible ? 'Visible' : 'Hidden' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="category-row-actions">
                                            <button
                                                type="button"
                                                class="category-edit-trigger"
                                                data-edit-category
                                                data-category-id="{{ $category->id }}"
                                                data-category-name="{{ $category->name }}"
                                                data-category-description="{{ $category->description }}"
                                                data-update-url="{{ route('admin.categories.update', $category) }}"
                                            >Edit</button>
                                            <form method="POST" action="{{ route('admin.categories.visibility', $category) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="category-visibility-trigger">
                                                    {{ $category->is_visible ? 'Hide' : 'Show' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <footer class="categories-table-footer">
                    <span>Showing {{ $categories->firstItem() }}–{{ $categories->lastItem() }} of {{ number_format($categories->total()) }} categories</span>
                    {{ $categories->links() }}
                </footer>
            @else
                <div class="categories-empty-state">
                    <strong>{{ ($filters['search'] ?? '') !== '' || ($filters['visibility'] ?? 'all') !== 'all' ? 'No matching categories' : 'No categories yet' }}</strong>
                    <span>{{ ($filters['search'] ?? '') !== '' || ($filters['visibility'] ?? 'all') !== 'all' ? 'Try adjusting your search or filters.' : 'Add a category to start organizing your artwork.' }}</span>
                    @if (($filters['search'] ?? '') !== '' || ($filters['visibility'] ?? 'all') !== 'all')
                        <a href="{{ route('admin.categories.index') }}" class="categories-filter-reset">Reset filters</a>
                    @endif
                </div>
            @endif
        </section>

        <aside class="categories-guidance" role="note">
            <strong>Keep your catalog organized</strong>
            <span>Hidden categories are removed from storefront navigation. Reassign artworks before changing a category if needed.</span>
        </aside>
        <p class="categories-footnote">Category counts reflect artworks assigned in your catalog, including drafts and archived work.</p>

        <dialog class="category-modal" id="category-modal" aria-labelledby="category-modal-title">
            <header class="category-modal-header">
                <div>
                    <span class="admin-eyebrow">STORE MANAGEMENT</span>
                    <h2 id="category-modal-title">Add category</h2>
                    <p>Create a category for organizing your artwork.</p>
                </div>
                <button type="button" class="category-modal-close" data-close-category-modal aria-label="Close add category dialog">×</button>
            </header>
            @if ($errors->any() && old('_form_context') === 'new-category')
                <div class="category-validation-summary" role="alert">
                    <strong>Please check the highlighted fields.</strong>
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.categories.store') }}" class="category-modal-form" id="new-category-form">
                @csrf
                <input type="hidden" name="_form_context" value="new-category">
                <label class="category-field">
                    <span>Category name <b aria-hidden="true">*</b></span>
                    <input type="text" name="name" value="{{ old('_form_context') === 'new-category' ? old('name', $newCategory->name) : '' }}" maxlength="100" required>
                    @if (old('_form_context') === 'new-category' && $errors->has('name'))<small class="category-field-error">{{ $errors->first('name') }}</small>@endif
                </label>
                <label class="category-field">
                    <span>Description</span>
                    <textarea name="description" rows="3" maxlength="500">{{ old('_form_context') === 'new-category' ? old('description', $newCategory->description) : '' }}</textarea>
                    @if (old('_form_context') === 'new-category' && $errors->has('description'))<small class="category-field-error">{{ $errors->first('description') }}</small>@endif
                </label>
                <footer class="category-modal-actions">
                    <button type="button" class="category-secondary-button" data-close-category-modal>Cancel</button>
                    <button type="submit" class="category-primary-button">Add category</button>
                </footer>
            </form>
        </dialog>

        <dialog class="category-modal" id="edit-category-modal" aria-labelledby="edit-category-modal-title">
            <header class="category-modal-header">
                <div>
                    <span class="admin-eyebrow">STORE MANAGEMENT</span>
                    <h2 id="edit-category-modal-title">Edit category</h2>
                    <p>Update this category and keep its artwork assignments.</p>
                </div>
                <button type="button" class="category-modal-close" data-close-edit-category aria-label="Close edit category dialog">×</button>
            </header>
            @if ($errors->any() && $editModalOpen)
                <div class="category-validation-summary" role="alert">
                    <strong>Please check the highlighted fields.</strong>
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            <form method="POST" action="{{ $editCategory->exists ? route('admin.categories.update', $editCategory) : '#' }}" class="category-modal-form" id="edit-category-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="_form_context" value="edit-category">
                <input type="hidden" name="_category_id" value="{{ $editCategory->exists ? $editCategory->id : '' }}">
                <label class="category-field">
                    <span>Category name <b aria-hidden="true">*</b></span>
                    <input type="text" name="name" value="{{ $editModalOpen ? old('name', $editCategory->name) : '' }}" maxlength="100" required>
                    @if ($editModalOpen && $errors->has('name'))<small class="category-field-error">{{ $errors->first('name') }}</small>@endif
                </label>
                <label class="category-field">
                    <span>Description</span>
                    <textarea name="description" rows="3" maxlength="500">{{ $editModalOpen ? old('description', $editCategory->description) : '' }}</textarea>
                    @if ($editModalOpen && $errors->has('description'))<small class="category-field-error">{{ $errors->first('description') }}</small>@endif
                </label>
                <footer class="category-modal-actions">
                    <button type="button" class="category-secondary-button" data-close-edit-category>Cancel</button>
                    <button type="submit" class="category-primary-button">Save changes</button>
                </footer>
            </form>
        </dialog>
    </main>
</div>
<script>
    const newCategoryModal = document.getElementById('category-modal');
    const editCategoryModal = document.getElementById('edit-category-modal');
    const editCategoryForm = document.getElementById('edit-category-form');
    let lastCategoryTrigger = null;
    let lastEditCategoryTrigger = null;

    document.getElementById('open-category-modal').addEventListener('click', (event) => {
        lastCategoryTrigger = event.currentTarget;
        newCategoryModal.showModal();
        newCategoryModal.querySelector('[name="name"]').focus();
    });

    document.querySelectorAll('[data-close-category-modal]').forEach((button) => {
        button.addEventListener('click', () => newCategoryModal.close());
    });

    document.querySelectorAll('[data-close-edit-category]').forEach((button) => {
        button.addEventListener('click', () => editCategoryModal.close());
    });

    document.querySelectorAll('[data-edit-category]').forEach((button) => {
        button.addEventListener('click', () => {
            lastEditCategoryTrigger = button;
            editCategoryForm.action = button.dataset.updateUrl;
            editCategoryForm.querySelector('[name="_category_id"]').value = button.dataset.categoryId;
            editCategoryForm.querySelector('[name="name"]').value = button.dataset.categoryName;
            editCategoryForm.querySelector('[name="description"]').value = button.dataset.categoryDescription;
            editCategoryModal.showModal();
            editCategoryForm.querySelector('[name="name"]').focus();
        });
    });

    newCategoryModal.addEventListener('click', (event) => {
        if (event.target === newCategoryModal) {
            newCategoryModal.close();
        }
    });

    editCategoryModal.addEventListener('click', (event) => {
        if (event.target === editCategoryModal) {
            editCategoryModal.close();
        }
    });

    newCategoryModal.addEventListener('close', () => lastCategoryTrigger?.focus());
    editCategoryModal.addEventListener('close', () => lastEditCategoryTrigger?.focus());

    @if ($errors->any() && old('_form_context') === 'new-category')
        newCategoryModal.showModal();
    @elseif ($editModalOpen)
        editCategoryModal.showModal();
    @endif
</script>
</body>
</html>
