@props([
    'title',
    'description',
    'active' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ $title }} — AZUL</title>
        <meta name="description" content="{{ $description }}" />

        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <x-storefront.header :active="$active ?? null" />

        <main>
            {{ $slot }}
        </main>

        <x-storefront.footer />

        <div id="cart-overlay" class="cart-overlay"></div>

        <aside id="cart-drawer" role="dialog" aria-modal="true" aria-label="Carrito de compras" class="cart-drawer">
            <header class="cart-drawer__header">
                <h2 class="title-display title-section">Tu carrito</h2>
                <button id="cart-close" type="button" aria-label="Cerrar carrito" class="cart-drawer__close">
                    <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </header>

            <div id="cart-items" class="cart-drawer__items"></div>

            <footer class="cart-drawer__footer">
                <div class="cart-total">
                    <span>Total</span>
                    <span id="cart-total" class="cart-total__value">$0.00</span>
                </div>
                <button id="checkout-button" type="button" class="btn btn--primary btn--block btn--large">
                    Finalizar compra
                </button>
            </footer>
        </aside>
    </body>
</html>
