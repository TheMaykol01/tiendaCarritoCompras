<x-layouts.app>
    <div class="sidebar-layout">
        <div class="settings-heading">
            <flux:heading size="xl" level="1">Editar producto</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ $product->name }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>

        <div class="card card__body">
            <x-forms.product-form
                :action="route('products.update', $product)"
                method="PUT"
                :product="$product"
                submit-label="Guardar cambios"
            />
        </div>
    </div>
</x-layouts.app>
