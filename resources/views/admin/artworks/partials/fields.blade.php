<div class="artwork-form-fields">
    @php
        $useFormOldInput = old('_form_context') === $formContext;
        $categoryListId = $formContext.'-artwork-categories';
    @endphp

    <label class="artwork-field">
        <span>Artwork title <b aria-hidden="true">*</b></span>
        <input type="text" name="title" value="{{ $useFormOldInput ? old('title', $artwork->title) : $artwork->title }}" maxlength="255" required>
        @if ($useFormOldInput && $errors->has('title'))<small class="field-error">{{ $errors->first('title') }}</small>@endif
    </label>

    <label class="artwork-field">
        <span>Artist name <b aria-hidden="true">*</b></span>
        <input type="text" name="artist_name" value="{{ $useFormOldInput ? old('artist_name', $artwork->artist_name) : $artwork->artist_name }}" maxlength="255" required>
        @if ($useFormOldInput && $errors->has('artist_name'))<small class="field-error">{{ $errors->first('artist_name') }}</small>@endif
    </label>

    <label class="artwork-field">
        <span>Category <b aria-hidden="true">*</b></span>
        <input type="text" name="category" value="{{ $useFormOldInput ? old('category', $artwork->category) : $artwork->category }}" list="{{ $categoryListId }}" maxlength="100" required>
        <datalist id="{{ $categoryListId }}">
            @foreach ($categories as $category)
                <option value="{{ $category }}">
            @endforeach
        </datalist>
        @if ($useFormOldInput && $errors->has('category'))<small class="field-error">{{ $errors->first('category') }}</small>@endif
    </label>

    <label class="artwork-field">
        <span>Price (USD) <b aria-hidden="true">*</b></span>
        <input type="number" name="price" value="{{ $useFormOldInput ? old('price', $artwork->price) : $artwork->price }}" min="0" max="99999999.99" step="0.01" required>
        @if ($useFormOldInput && $errors->has('price'))<small class="field-error">{{ $errors->first('price') }}</small>@endif
    </label>

    <label class="artwork-field">
        <span>Publication status <b aria-hidden="true">*</b></span>
        <select name="status" required>
            @foreach (['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $value => $label)
                <option value="{{ $value }}" @selected(($useFormOldInput ? old('status', $artwork->status) : $artwork->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @if ($useFormOldInput && $errors->has('status'))<small class="field-error">{{ $errors->first('status') }}</small>@endif
    </label>

    <label class="artwork-field artwork-description-field">
        <span>Description <b aria-hidden="true">*</b></span>
        <textarea name="description" rows="5" maxlength="5000" required>{{ $useFormOldInput ? old('description', $artwork->description) : $artwork->description }}</textarea>
        @if ($useFormOldInput && $errors->has('description'))<small class="field-error">{{ $errors->first('description') }}</small>@endif
    </label>

    <label class="artwork-field artwork-image-field">
        <span>Artwork image @if (! $isEditing)<b aria-hidden="true">*</b>@endif</span>
        @if ($isEditing)
            <span class="artwork-current-image" data-current-image-container @if (! $artwork->exists) hidden @endif>
                <img
                    src="{{ $artwork->exists ? asset($artwork->image_path) : '' }}"
                    alt="{{ $artwork->exists ? 'Current image for '.$artwork->title : '' }}"
                    data-current-image
                >
                <span>Current image. Choose a new image to replace it.</span>
            </span>
        @endif
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" {{ $isEditing ? '' : 'required' }}>
        <small class="artwork-field-hint">{{ $isEditing ? 'Optional. Choose a new image to replace the current one. JPG, PNG, or WebP, up to 5 MB.' : 'JPG, PNG, or WebP. Maximum file size: 5 MB.' }}</small>
        @if ($useFormOldInput && $errors->has('image'))<small class="field-error">{{ $errors->first('image') }}</small>@endif
    </label>
</div>
