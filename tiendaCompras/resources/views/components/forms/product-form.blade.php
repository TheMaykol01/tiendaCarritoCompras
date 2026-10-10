@props([
    'action',
    'method' => 'POST',
    'product' => null,
    'submitLabel' => 'Guardar',
])

<form method="POST" action="{{ $action }}" class="stack">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="stack--tight">
        @error('name')
            <p class="text-danger text-sm">{{ $message }}</p>
        @enderror
        <label class="field">
            <span class="field__label">Nombre</span>
            <input
                type="text"
                name="name"
                value="{{ old('name', $product?->name) }}"
                required
                maxlength="255"
                placeholder="Laptop Aura Pro 14"
                class="field__control"
            />
        </label>
    </div>

    <div class="settings-grid">
        <div class="stack--tight">
            @error('sku')
                <p class="text-danger text-sm">{{ $message }}</p>
            @enderror
            <label class="field">
                <span class="field__label">SKU</span>
                <input
                    type="text"
                    name="sku"
                    value="{{ old('sku', $product?->sku) }}"
                    required
                    maxlength="255"
                    placeholder="AZUL-LAP-001"
                    class="field__control"
                />
            </label>
        </div>

        <div class="stack--tight">
            @error('category')
                <p class="text-danger text-sm">{{ $message }}</p>
            @enderror
            <label class="field">
                <span class="field__label">Categoría</span>
                <input
                    type="text"
                    name="category"
                    value="{{ old('category', $product?->category) }}"
                    required
                    list="category-options"
                    placeholder="Computación"
                    class="field__control"
                />
                <datalist id="category-options">
                    <option value="Computación"></option>
                    <option value="Audio"></option>
                    <option value="Wearables"></option>
                    <option value="Móviles"></option>
                    <option value="Fotografía"></option>
                </datalist>
            </label>
        </div>

        <div class="stack--tight">
            @error('price')
                <p class="text-danger text-sm">{{ $message }}</p>
            @enderror
            <label class="field">
                <span class="field__label">Precio (USD)</span>
                <input
                    type="number"
                    name="price"
                    value="{{ old('price', $product?->price) }}"
                    required
                    step="0.01"
                    min="0"
                    placeholder="1299.00"
                    class="field__control"
                />
            </label>
        </div>
    </div>

    <label class="field">
        <span class="field__label">Descripción</span>
        <textarea
            name="description"
            rows="3"
            maxlength="1000"
            placeholder="Opcional: detalles del producto"
            class="field__control"
        >{{ old('description', $product?->description) }}</textarea>
    </label>

    <label class="fieldset__option">
        <input type="hidden" name="is_active" value="0" />
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true)) class="mt-1" />
        <span class="text-sm text-body">Visible en la tienda pública</span>
    </label>

    <div class="form-actions">
        <a href="{{ route('products.index') }}" class="button button--outline">Cancelar</a>
        <button type="submit" class="button button--primary">{{ $submitLabel }}</button>
    </div>
</form>
