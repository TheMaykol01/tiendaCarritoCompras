<x-layouts.app>
    <div class="sidebar-layout">
        <div class="settings-heading">
            <flux:heading size="xl" level="1">Productos</flux:heading>
            <flux:subheading size="lg" class="mb-6">Alta, edición y baja del catálogo de la tienda</flux:subheading>
            <flux:separator variant="subtle" />
        </div>

        @if (session('status'))
            <div class="card text-success">{{ session('status') }}</div>
        @endif

        <div class="form-actions form-actions--between">
            <p class="text-sm text-muted">{{ $products->total() }} producto(s) en el catálogo</p>
            <a href="{{ route('products.create') }}" class="button button--primary">Agregar producto</a>
        </div>

        @if ($products->isEmpty())
            <div class="card text-center text-sm text-muted">
                Aún no hay productos en el catálogo.
            </div>
        @else
            <div class="settings-grid">
                @foreach ($products as $product)
                    <article class="card card__body stack--tight">
                        <div class="form-actions form-actions--between">
                            <h2 class="text-strong">{{ $product->name }}</h2>
                            <span @class([
                                'text-xs',
                                'text-success' => $product->is_active,
                                'text-muted' => ! $product->is_active,
                            ])>
                                {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <p class="text-sm text-muted">{{ $product->category }} · {{ $product->sku }}</p>
                        <p class="text-strong">${{ number_format($product->price, 2) }}</p>
                        <p class="text-xs text-muted">
                            Inventario: {{ $product->available_stock ?? 0 }} unidad(es)
                        </p>

                        <div class="form-actions form-actions--between">
                            <a href="{{ route('products.edit', $product) }}" class="button button--outline button--compact">Editar</a>

                            <form method="POST" action="{{ route('products.destroy', $product) }}"
                                  onsubmit="return confirm('¿Eliminar {{ $product->name }} del catálogo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button button--danger button--compact">Eliminar</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <div>{{ $products->links() }}</div>
        @endif
    </div>
</x-layouts.app>
