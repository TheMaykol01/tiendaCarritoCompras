<x-layouts.app>
    <div class="sidebar-layout">
        <div class="settings-heading">
            <flux:heading size="xl" level="1">Agregar producto</flux:heading>
            <flux:subheading size="lg" class="mb-6">El SKU identifica el producto en todo el sistema</flux:subheading>
            <flux:separator variant="subtle" />
        </div>

        <div class="card card__body">
            <x-forms.product-form
                :action="route('products.store')"
                submit-label="Agregar al catálogo"
            />
        </div>
    </div>
</x-layouts.app>
