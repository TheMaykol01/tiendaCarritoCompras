<x-storefront.layout title="Inicio" description="AZUL: tienda online de tecnología. Laptops, audio, wearables y más con envío en 24h." active="home">
    <section class="hero">
        <div class="container hero__inner">
            <div class="hero__content">
                <p class="hero__badge">
                    <span class="hero__badge-dot"></span>
                    Envío gratis desde $50
                </p>

                <h1 class="hero__title">
                    Tecnología que
                    <span>se siente tuya</span>
                </h1>

                <p class="hero__lead">
                    Laptops, audio y wearables seleccionados. Paga al recibir y devuelve sin preguntas dentro de 30 días.
                </p>

                <div class="hero__actions">
                    <a @class(['btn', 'btn--primary']) href="{{ route('catalog') }}">Ver productos</a>
                    <a @class(['btn', 'btn--outline']) href="{{ route('stores') }}">Encuéntranos</a>
                </div>
            </div>

            <figure class="hero__art" aria-hidden="true">
                <div class="hero__art-grid">
                    <div @class(['hero__art-tile', 'hero__art-tile--brand'])>
                        <svg class="icon icon--lg" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 5.5h12v10H6z"/><path d="M3.5 18.5h17"/></svg>
                    </div>
                    <div @class(['hero__art-tile', 'hero__art-tile--paper'])>
                        <svg class="icon icon--lg" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 13c0-4.4 3.6-8 8-8s8 3.6 8 8"/><rect x="3" y="13" width="4" height="6" rx="1.5"/><rect x="17" y="13" width="4" height="6" rx="1.5"/></svg>
                    </div>
                </div>

                <span class="hero__watermark">AZUL</span>
            </figure>
        </div>
    </section>
</x-storefront.layout>
