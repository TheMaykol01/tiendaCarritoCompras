<x-storefront.layout title="Sucursales" description="Encuentra tu sucursal AZUL más cercana y pide delivery por WhatsApp." active="stores">
    <div class="page--tinted">
        <section aria-labelledby="branches-title" class="container page">
            <div class="section--center">
                <p class="eyebrow">Encuéntranos</p>
                <h1 id="branches-title" class="title-display title-page">Cerca de ti, en toda la ciudad</h1>
                <p class="lead">Visítanos en nuestras sucursales o pide a domicilio por WhatsApp. Cada tienda despacha en su zona.</p>
            </div>

            <div class="branch-grid">
                @foreach ($stores as $store)
                    <article class="branch-card">
                        <div class="branch-card__icon">
                            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-5.1-7-11a7 7 0 1 1 14 0c0 5.9-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                        </div>

                        <h3 class="branch-card__name">{{ $store->name }}</h3>
                        <p class="branch-card__address">{{ $store->address }}</p>

                        <div>
                            <p class="branch-card__zones-title">Delivery disponible en</p>
                            <div class="branch-card__zones">
                                @foreach ($store->zones as $zone)
                                    <span class="branch-card__zone">{{ $zone }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="branch-card__actions">
                            <a @class(['btn', 'btn--primary', 'btn--small', 'btn--compact', 'btn--grow']) href="https://wa.me/{{ $store->whatsapp }}" target="_blank" rel="noopener">
                                <svg class="icon--xs" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a9 9 0 0 1-13.4 7.8L3 21l1.2-4.6A9 9 0 1 1 21 12z"/></svg>
                                Pedir delivery
                            </a>
                            <a @class(['btn', 'btn--outline', 'btn--small', 'btn--compact']) href="{{ $store->mapUrl }}" target="_blank" rel="noopener">
                                Ubícanos
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
</x-storefront.layout>
