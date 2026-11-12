<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artworks | ARTFOLIO Admin</title>
    <meta name="description" content="Manage the artwork catalog in ARTFOLIO.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-artworks.css') }}">
</head>
<body>
<div class="admin-layout">
    @include('admin.partials.sidebar', ['active' => 'artworks', 'artworkCount' => $stats['total']])

    <main class="admin-main">
        <header class="admin-page-heading artworks-page-heading">
            <div>
                <span class="admin-eyebrow">STORE MANAGEMENT</span>
                <h1>Artworks</h1>
                <p>Manage your digital catalog, artwork images, and publication status.</p>
            </div>
            <button type="button" class="artwork-primary-button" id="open-artwork-modal">
                <span aria-hidden="true">+</span>
                Add artwork
            </button>
        </header>

        @if (session('status'))
            <div class="artwork-notice" role="status">{{ session('status') }}</div>
        @endif

        @error('artwork')
            <div class="artwork-validation-summary" role="alert">{{ $message }}</div>
        @enderror

        <section class="artwork-overview" aria-label="Artwork catalog totals">
            <div><span>Total artworks</span><strong>{{ number_format($stats['total']) }}</strong></div>
            <div><span>Published</span><strong>{{ number_format($stats['published']) }}</strong></div>
            <div><span>Drafts</span><strong>{{ number_format($stats['draft']) }}</strong></div>
        </section>

        <section class="admin-panel artwork-table-panel" aria-label="Artwork catalog">
            <form method="GET" action="{{ route('admin.artworks.index') }}" class="artwork-filter-bar">
                <label class="artwork-search">
                    <span class="visually-hidden">Search artwork title or artist</span>
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m16 16 4 4"></path>
                    </svg>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by artwork title or artist">
                </label>

                <label class="artwork-filter">
                    <span class="visually-hidden">Filter by category</span>
                    <select name="category">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->name }}" @selected(($filters['category'] ?? '') === $category->name)>{{ $category->name }}{{ $category->is_visible ? '' : ' (hidden)' }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="artwork-filter">
                    <span class="visually-hidden">Filter by status</span>
                    <select name="status">
                        <option value="">All statuses</option>
                        @foreach (['published' => 'Published', 'draft' => 'Draft', 'archived' => 'Archived'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="artwork-filter artwork-sort">
                    <span class="visually-hidden">Sort artworks</span>
                    <select name="sort">
                        <option value="newest" @selected($sort === 'newest')>Newest first</option>
                        <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
                        <option value="title" @selected($sort === 'title')>Title A–Z</option>
                        <option value="price_low" @selected($sort === 'price_low')>Price low–high</option>
                        <option value="price_high" @selected($sort === 'price_high')>Price high–low</option>
                    </select>
                </label>

                <button type="submit" class="artwork-filter-submit">Apply</button>
                <a href="{{ route('admin.artworks.index') }}" class="artwork-filter-reset">Reset</a>
            </form>

            @if ($artworks->isNotEmpty())
                <div class="artwork-table-scroll">
                    <table class="artwork-table">
                        <thead>
                            <tr>
                                <th scope="col">ARTWORK</th>
                                <th scope="col">ARTIST</th>
                                <th scope="col">CATEGORY</th>
                                <th scope="col">PRICE</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($artworks as $artwork)
                                <tr>
                                    <td>
                                        <div class="artwork-title-cell">
                                            <img src="{{ asset($artwork->image_path) }}" alt="" loading="lazy">
                                            <span>
                                                <strong>{{ $artwork->title }}</strong>
                                                <small>{{ collect([$artwork->image_width && $artwork->image_height ? $artwork->image_width.' × '.$artwork->image_height.' px' : null, $artwork->file_format ? strtoupper($artwork->file_format) : null])->filter()->implode(' · ') ?: 'Digital artwork' }}</small>
                                            </span>
                                        </div>
                                    </td>
                                    <td>{{ $artwork->artist_name }}</td>
                                    <td><span class="artwork-category-chip">{{ $artwork->category }}</span></td>
                                    <td class="artwork-price">@peso((float) $artwork->price)</td>
                                    <td>
                                        <span class="artwork-status status-{{ $artwork->status }}">{{ ucfirst($artwork->status) }}</span>
                                        @if (! $artwork->isAvailableForPurchase())
                                            <span class="artwork-status status-{{ $artwork->availability }}">{{ ucfirst($artwork->availability) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="artwork-row-actions">
                                            <button type="button" class="artwork-edit-trigger" data-open-edit-modal data-artwork-id="{{ $artwork->id }}">Edit</button>
                                            <button
                                                type="button"
                                                class="artwork-preview-trigger"
                                                aria-label="Preview {{ $artwork->title }}"
                                                data-artwork-preview
                                                data-artwork-id="{{ $artwork->id }}"
                                                data-title="{{ $artwork->title }}"
                                                data-artist="{{ $artwork->artist_name }}"
                                                data-category="{{ $artwork->category }}"
                                                data-price="@peso((float) $artwork->price)"
                                                data-price-value="{{ number_format((float) $artwork->price, 2, '.', '') }}"
                                                data-status="{{ $artwork->status }}"
                                                data-description="{{ $artwork->description }}"
                                                data-canvas-size="{{ $artwork->canvas_size }}"
                                                data-image="{{ asset($artwork->image_path) }}"
                                                data-edit-url="{{ route('admin.artworks.update', $artwork) }}"
                                                data-client-url="{{ route('artworks.show', ['slug' => $artwork->slug]) }}"
                                            >Preview</button>
                                            <form method="POST" action="{{ route('admin.artworks.destroy', $artwork) }}" onsubmit="return confirm('Remove this artwork from the catalog?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" aria-label="Delete {{ $artwork->title }}">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <footer class="artwork-table-footer">
                    <span>Showing {{ $artworks->firstItem() }}–{{ $artworks->lastItem() }} of {{ $artworks->total() }} artworks</span>
                    {{ $artworks->links() }}
                </footer>
            @else
                <div class="artwork-empty-state">
                    <strong>{{ $hasFilters ? 'No matching artworks' : 'Your catalog is empty' }}</strong>
                    <span>{{ $hasFilters ? 'Try changing your search or filters.' : 'Add your first artwork to start building the catalog.' }}</span>
                    @if (! $hasFilters)
                        <button type="button" class="artwork-primary-button" id="open-artwork-modal-empty">Add artwork</button>
                    @endif
                </div>
            @endif
        </section>

        <dialog class="artwork-modal" id="artwork-modal" aria-labelledby="artwork-modal-title">
            <header class="artwork-modal-header">
                <div>
                    <span class="admin-eyebrow">STORE MANAGEMENT</span>
                    <h2 id="artwork-modal-title">Add artwork</h2>
                    <p>Add a new digital piece to your catalog.</p>
                </div>
                <button type="button" class="artwork-modal-close" data-close-artwork-modal aria-label="Close add artwork dialog">×</button>
            </header>

            @if ($errors->any() && old('_form_context') === 'artwork')
                <div class="artwork-validation-summary artwork-modal-errors" role="alert">
                    <strong>Please check the highlighted fields.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('admin.artworks.store') }}"
                enctype="multipart/form-data"
                class="artwork-form artwork-modal-form"
            >
                @csrf
                <input type="hidden" name="_form_context" value="artwork">
                @include('admin.artworks.partials.fields', [
                    'artwork' => $newArtwork,
                    'formContext' => 'artwork',
                    'isEditing' => false,
                ])

                <div class="artwork-form-actions">
                    <button type="button" class="artwork-secondary-button" data-close-artwork-modal>Cancel</button>
                    <button type="submit" class="artwork-primary-button">Add artwork</button>
                </div>
            </form>
        </dialog>

        <dialog class="artwork-modal" id="artwork-edit-modal" aria-labelledby="artwork-edit-modal-title">
            <header class="artwork-modal-header">
                <div>
                    <span class="admin-eyebrow">STORE MANAGEMENT</span>
                    <h2 id="artwork-edit-modal-title">Edit artwork</h2>
                    <p>Update the details shown in your catalog.</p>
                </div>
                <button type="button" class="artwork-modal-close" data-close-edit-modal aria-label="Close edit artwork dialog">×</button>
            </header>

            @if ($errors->any() && $editModalOpen)
                <div class="artwork-validation-summary artwork-modal-errors" role="alert">
                    <strong>Please check the highlighted fields.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ $editArtwork->exists ? route('admin.artworks.update', $editArtwork) : '#' }}"
                enctype="multipart/form-data"
                class="artwork-form artwork-modal-form"
                id="artwork-edit-form"
            >
                @csrf
                @method('PUT')
                <input type="hidden" name="_form_context" value="edit-artwork">
                <input type="hidden" name="_artwork_id" value="{{ $editArtwork->exists ? $editArtwork->id : '' }}">
                @include('admin.artworks.partials.fields', [
                    'artwork' => $editArtwork,
                    'formContext' => 'edit-artwork',
                    'isEditing' => true,
                ])

                <div class="artwork-form-actions">
                    <button type="button" class="artwork-secondary-button" data-close-edit-modal>Cancel</button>
                    <button type="submit" class="artwork-primary-button">Save changes</button>
                </div>
            </form>
        </dialog>

        <dialog class="artwork-preview-drawer" id="artwork-preview-drawer" aria-labelledby="artwork-preview-title">
            <header class="artwork-preview-header">
                <div>
                    <span class="admin-eyebrow">ARTWORK DETAILS</span>
                    <p>Catalog preview</p>
                </div>
                <button type="button" class="artwork-modal-close" data-close-artwork-preview aria-label="Close artwork preview">×</button>
            </header>

            <div class="artwork-preview-content">
                <img class="artwork-preview-image" data-preview-image alt="">
                <div class="artwork-preview-labels">
                    <span class="artwork-category-chip" data-preview-category></span>
                    <span class="artwork-status" data-preview-status></span>
                </div>
                <h2 id="artwork-preview-title" data-preview-title></h2>
                <p class="artwork-preview-artist">By <span data-preview-artist></span></p>

                <section class="artwork-preview-price" aria-label="Artwork price">
                    <span>Price</span>
                    <strong data-preview-price></strong>
                </section>

                <section class="artwork-preview-description" aria-labelledby="artwork-preview-description-heading">
                    <h3 id="artwork-preview-description-heading">Description</h3>
                    <p data-preview-description></p>
                </section>
            </div>

            <footer class="artwork-preview-actions">
                <a class="artwork-secondary-button" data-preview-client-link target="_blank" rel="noopener">View client page</a>
                <button type="button" class="artwork-primary-button" data-preview-edit-link>Edit artwork</button>
            </footer>
        </dialog>
    </main>
</div>
<script>
    const artworkModal = document.getElementById('artwork-modal');
    const artworkEditModal = document.getElementById('artwork-edit-modal');
    const artworkEditForm = document.getElementById('artwork-edit-form');
    const artworkPreviewDrawer = document.getElementById('artwork-preview-drawer');
    const openArtworkModalButtons = [
        document.getElementById('open-artwork-modal'),
        document.getElementById('open-artwork-modal-empty')
    ].filter(Boolean);
    let lastArtworkModalTrigger = null;
    let lastEditTrigger = null;
    let lastPreviewTrigger = null;

    const openEditModal = (artworkTrigger) => {
        if (!artworkTrigger) {
            return;
        }

        artworkEditModal.querySelectorAll('.field-error, .artwork-modal-errors').forEach((error) => error.remove());
        lastEditTrigger = artworkTrigger.closest('tr').querySelector('[data-open-edit-modal]');
        artworkEditForm.action = artworkTrigger.dataset.editUrl;
        artworkEditForm.querySelector('[name="_artwork_id"]').value = artworkTrigger.dataset.artworkId;
        artworkEditForm.querySelector('[name="title"]').value = artworkTrigger.dataset.title;
        artworkEditForm.querySelector('[name="artist_name"]').value = artworkTrigger.dataset.artist;
        artworkEditForm.querySelector('[name="category"]').value = artworkTrigger.dataset.category;
        artworkEditForm.querySelector('[name="price"]').value = artworkTrigger.dataset.priceValue;
        artworkEditForm.querySelector('[name="status"]').value = artworkTrigger.dataset.status;
        artworkEditForm.querySelector('[name="description"]').value = artworkTrigger.dataset.description;
        artworkEditForm.querySelector('[name="canvas_size"]').value = artworkTrigger.dataset.canvasSize ?? '';
        artworkEditForm.querySelector('[name="image"]').value = '';

        const currentImage = artworkEditForm.querySelector('[data-current-image]');
        currentImage.src = artworkTrigger.dataset.image;
        currentImage.alt = `Current image for ${artworkTrigger.dataset.title}`;
        artworkEditForm.querySelector('[data-current-image-container]').hidden = false;

        if (artworkPreviewDrawer.open) {
            artworkPreviewDrawer.close();
        }
        artworkEditModal.showModal();
        artworkEditModal.querySelector('[name="title"]').focus();
    };

    document.querySelectorAll('[data-open-edit-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            openEditModal(button.closest('tr').querySelector('[data-artwork-preview]'));
        });
    });

    artworkEditModal.querySelectorAll('[data-close-edit-modal]').forEach((button) => {
        button.addEventListener('click', () => artworkEditModal.close());
    });

    artworkEditModal.addEventListener('click', (event) => {
        if (event.target === artworkEditModal) {
            artworkEditModal.close();
        }
    });

    artworkEditModal.addEventListener('close', () => {
        lastEditTrigger?.focus();
    });

    openArtworkModalButtons.forEach((button) => {
        button.addEventListener('click', () => {
            lastArtworkModalTrigger = button;
            artworkModal.showModal();
            artworkModal.querySelector('input[name="title"]').focus();
        });
    });

    artworkModal.querySelectorAll('[data-close-artwork-modal]').forEach((button) => {
        button.addEventListener('click', () => artworkModal.close());
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && artworkModal.open) {
            event.preventDefault();
            artworkModal.close();
        } else if (event.key === 'Escape' && artworkEditModal.open) {
            event.preventDefault();
            artworkEditModal.close();
        } else if (event.key === 'Escape' && artworkPreviewDrawer.open) {
            event.preventDefault();
            artworkPreviewDrawer.close();
        }
    });

    artworkModal.addEventListener('click', (event) => {
        if (event.target === artworkModal) {
            artworkModal.close();
        }
    });

    artworkModal.addEventListener('close', () => {
        lastArtworkModalTrigger?.focus();
    });

    const previewFields = {
        image: artworkPreviewDrawer.querySelector('[data-preview-image]'),
        title: artworkPreviewDrawer.querySelector('[data-preview-title]'),
        artist: artworkPreviewDrawer.querySelector('[data-preview-artist]'),
        category: artworkPreviewDrawer.querySelector('[data-preview-category]'),
        price: artworkPreviewDrawer.querySelector('[data-preview-price]'),
        status: artworkPreviewDrawer.querySelector('[data-preview-status]'),
        description: artworkPreviewDrawer.querySelector('[data-preview-description]'),
        clientLink: artworkPreviewDrawer.querySelector('[data-preview-client-link]'),
        editButton: artworkPreviewDrawer.querySelector('[data-preview-edit-link]')
    };

    document.querySelectorAll('[data-artwork-preview]').forEach((button) => {
        button.addEventListener('click', () => {
            lastPreviewTrigger = button;
            previewFields.image.src = button.dataset.image;
            previewFields.image.alt = button.dataset.title;
            previewFields.title.textContent = button.dataset.title;
            previewFields.artist.textContent = button.dataset.artist;
            previewFields.category.textContent = button.dataset.category;
            previewFields.price.textContent = button.dataset.price;
            previewFields.status.textContent = button.dataset.status.charAt(0).toUpperCase() + button.dataset.status.slice(1);
            previewFields.status.className = `artwork-status status-${button.dataset.status}`;
            previewFields.description.textContent = button.dataset.description;
            previewFields.clientLink.href = button.dataset.clientUrl;
            artworkPreviewDrawer.showModal();
            artworkPreviewDrawer.querySelector('[data-close-artwork-preview]').focus();
        });
    });

    artworkPreviewDrawer.querySelector('[data-close-artwork-preview]').addEventListener('click', () => {
        artworkPreviewDrawer.close();
    });

    artworkPreviewDrawer.addEventListener('click', (event) => {
        if (event.target === artworkPreviewDrawer) {
            artworkPreviewDrawer.close();
        }
    });

    artworkPreviewDrawer.addEventListener('close', () => {
        lastPreviewTrigger?.focus();
    });

    previewFields.editButton.addEventListener('click', () => {
        openEditModal(lastPreviewTrigger);
    });

    @if ($errors->any() && old('_form_context') === 'artwork')
        lastArtworkModalTrigger = document.getElementById('open-artwork-modal');
        artworkModal.showModal();
        artworkModal.querySelector('.field-error')?.closest('.artwork-field').querySelector('input, select, textarea')?.focus();
    @elseif ($errors->any() && $editModalOpen)
        const editArtworkId = '{{ $editArtwork->id }}';
        lastEditTrigger = Array.from(document.querySelectorAll('[data-open-edit-modal]'))
            .find((button) => button.dataset.artworkId === editArtworkId) ?? null;
        artworkEditModal.showModal();
        artworkEditModal.querySelector('.field-error')?.closest('.artwork-field').querySelector('input, select, textarea')?.focus();
    @endif
</script>
</body>
</html>
