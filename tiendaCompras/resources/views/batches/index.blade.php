<x-layouts.app>
    <div class="sidebar-layout">
        <div class="settings-heading">
            <flux:heading size="xl" level="1">Inventario por lotes</flux:heading>
            <flux:subheading size="lg" class="mb-6">Cada lote muestra su código de barras para escaneo</flux:subheading>
            <flux:separator variant="subtle" />
        </div>

        @if (session('status'))
            <div class="card text-success">{{ session('status') }}</div>
        @endif

        <div class="form-actions form-actions--between">
            <p class="text-sm text-muted">{{ $batches->total() }} lote(s) registrado(s)</p>
            <div class="form-actions">
                <a href="{{ route('inventory.index') }}" class="button button--outline">Escanear</a>
                <a href="{{ route('batches.create') }}" class="button button--primary">Registrar lote</a>
            </div>
        </div>

        @if ($batches->isEmpty())
            <div class="card text-center text-sm text-muted">
                Aún no hay lotes en el inventario.
            </div>
        @else
            <div class="settings-grid">
                @foreach ($batches as $batch)
                    <article class="card card__body stack--tight">
                        <div class="form-actions form-actions--between">
                            <h2 class="text-strong">{{ $batch->product->name }}</h2>
                            <span class="text-xs text-muted">{{ $batch->batch_number }}</span>
                        </div>

                        <img
                            class="barcode-image"
                            src="{{ route('batches.barcode', $batch) }}"
                            alt="Código de barras {{ $batch->barcode }}"
                        />

                        <p class="text-xs text-muted text-nowrap">{{ $batch->barcode }}</p>

                        <p class="text-sm text-body">
                            Inventario: <span class="text-strong">{{ $batch->quantity }}</span> unidad(es)
                        </p>

                        @if ($batch->expiry_date)
                            <p class="text-xs text-muted">
                                Vence: {{ $batch->expiry_date->format('d/m/Y') }}
                            </p>
                        @endif

                        <div class="form-actions form-actions--start">
                            <form method="POST" action="{{ route('batches.destroy', $batch) }}"
                                  onsubmit="return confirm('¿Eliminar el lote {{ $batch->batch_number }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button button--danger button--compact">Eliminar</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            <div>{{ $batches->links() }}</div>
        @endif
    </div>
</x-layouts.app>
