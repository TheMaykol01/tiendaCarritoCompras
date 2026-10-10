<x-layouts.app>
    <div class="sidebar-layout">
        <div class="settings-heading">
            <flux:heading size="xl" level="1">Registrar lote</flux:heading>
            <flux:subheading size="lg" class="mb-6">Cada lote recibe un código de barras único</flux:subheading>
            <flux:separator variant="subtle" />
        </div>

        <div class="card card__body">
            <x-forms.batch-form
                :products="$products"
                :action="route('batches.store')"
            />
        </div>
    </div>
</x-layouts.app>
