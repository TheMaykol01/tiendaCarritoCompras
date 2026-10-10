@props(['products', 'action'])

<form method="POST" action="{{ $action }}" class="stack">
    @csrf

    <div class="stack--tight">
        @error('product_id')
            <p class="text-danger text-sm">{{ $message }}</p>
        @enderror
        <label class="field">
            <span class="field__label">Producto</span>
            <select name="product_id" required class="field__control">
                <option value="" disabled @selected(old('product_id') === null)>Selecciona un producto</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @selected((int) old('product_id') === $product->id)>
                        {{ $product->name }} — {{ $product->sku }}
                    </option>
                @endforeach
            </select>
        </label>
    </div>

    <div class="settings-grid">
        <div class="stack--tight">
            @error('expiry_date')
                <p class="text-danger text-sm">{{ $message }}</p>
            @enderror
            <label class="field">
                <span class="field__label">Fecha de vencimiento</span>
                <input
                    type="date"
                    name="expiry_date"
                    value="{{ old('expiry_date') }}"
                    class="field__control"
                />
            </label>
        </div>

        <div class="stack--tight">
            @error('quantity')
                <p class="text-danger text-sm">{{ $message }}</p>
            @enderror
            <label class="field">
                <span class="field__label">Cantidad inicial</span>
                <input
                    type="number"
                    name="quantity"
                    value="{{ old('quantity', 0) }}"
                    required
                    step="1"
                    min="0"
                    class="field__control"
                />
            </label>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('batches.index') }}" class="button button--outline">Cancelar</a>
        <button type="submit" class="button button--primary">Registrar lote</button>
    </div>
</form>
