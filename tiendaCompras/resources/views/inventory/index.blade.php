<x-layouts.app>
    <div class="sidebar-layout">
        <div class="settings-heading">
            <flux:heading size="xl" level="1">Escanear inventario</flux:heading>
            <flux:subheading size="lg" class="mb-6">Escanea o escribe el código de barras para registrar entradas y salidas</flux:subheading>
            <flux:separator variant="subtle" />
        </div>

        <div class="card card__body stack">
            <div class="form-actions form-actions--between">
                <p class="text-sm text-muted">Usa la cámara del dispositivo o el campo manual.</p>
                <button type="button" id="start-camera" class="button button--outline">Iniciar cámara</button>
            </div>

            <div id="reader" class="scanner"></div>

            <form id="stock-form" method="POST" action="{{ route('inventory.adjust') }}" class="stack">
                @csrf

                <label class="field">
                    <span class="field__label">Código de barras</span>
                    <input type="text" id="barcode" name="barcode" required class="field__control"
                           placeholder="AZXXXXXXXXXXXXXXXX" />
                </label>

                <div class="settings-grid">
                    <label class="field">
                        <span class="field__label">Movimiento</span>
                        <select name="type" class="field__control">
                            <option value="in">Entrada</option>
                            <option value="out">Salida</option>
                        </select>
                    </label>

                    <label class="field">
                        <span class="field__label">Cantidad</span>
                        <input type="number" name="quantity" value="1" min="1" step="1" required class="field__control" />
                    </label>
                </div>

                <div class="form-actions form-actions--between">
                    <p id="scan-result" class="text-sm text-muted" role="status" aria-live="polite"></p>
                    <button type="submit" class="button button--primary">Aplicar movimiento</button>
                </div>
            </form>
        </div>

        <div class="settings-heading">
            <flux:heading size="lg" level="2">Últimos movimientos</flux:heading>
            <flux:separator variant="subtle" />
        </div>

        @if ($logs->isEmpty())
            <div class="card text-center text-sm text-muted">
                Todavía no se han registrado movimientos.
            </div>
        @else
            <div class="card card__body stack--tight">
                @foreach ($logs as $log)
                    <div class="form-actions form-actions--between">
                        <span class="text-sm text-body">
                            {{ $log->type === \App\Models\Batch::TYPE_IN ? 'Entrada' : 'Salida' }}
                            · {{ $log->quantity }} unidad(es)
                            · {{ $log->batch->product->name }}
                        </span>
                        <span class="text-xs text-muted">
                            {{ $log->batch->batch_number }} · {{ $log->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode" defer></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('stock-form');
                const barcodeInput = document.getElementById('barcode');
                const result = document.getElementById('scan-result');
                const startButton = document.getElementById('start-camera');
                let scanner = null;

                startButton?.addEventListener('click', async () => {
                    if (typeof Html5Qrcode === 'undefined') {
                        result.textContent = 'El lector aún no está disponible.';
                        return;
                    }

                    scanner = scanner || new Html5Qrcode('reader');

                    try {
                        await scanner.start(
                            { facingMode: 'environment' },
                            { fps: 10, qrbox: 250 },
                            (decodedText) => {
                                barcodeInput.value = decodedText;
                                result.textContent = 'Código detectado: ' + decodedText;
                            },
                        );
                        startButton.disabled = true;
                    } catch (error) {
                        result.textContent = 'No se pudo acceder a la cámara.';
                    }
                });

                form?.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    result.textContent = 'Enviando…';

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                            },
                            body: JSON.stringify({
                                barcode: barcodeInput.value,
                                type: form.querySelector('select[name="type"]').value,
                                quantity: form.querySelector('input[name="quantity"]').value,
                            }),
                        });

                        const data = await response.json();
                        result.textContent = data.message ?? (response.ok ? 'Movimiento aplicado.' : 'Revisa los datos.');
                    } catch (error) {
                        result.textContent = 'No se pudo conectar con el servidor.';
                    }
                });
            });
        </script>
    @endpush
</x-layouts.app>
