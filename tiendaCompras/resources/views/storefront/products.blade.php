<x-storefront.layout title="Productos" description="Catálogo AZUL: laptops, audio, wearables y más con envío en 24h." active="catalog">
    <div class="container page">
        <section aria-labelledby="catalog-title">
            <div class="catalog__header">
                <div>
                    <p class="eyebrow">Categorías</p>
                    <h1 id="catalog-title" class="title-display title-page">Catálogo</h1>
                    <p id="catalog-count" class="catalog__count"></p>
                </div>

                <label class="search">
                    <span class="sr-only">Buscar productos</span>
                    <svg class="search__icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                    <input id="search-input" type="search" placeholder="Buscar productos…" class="search__input">
                </label>
            </div>

            <div id="category-chips" class="chip-list">
                <button type="button" data-category="" class="chip chip--active">Todo</button>

                @foreach ($categories as $category)
                    <button type="button" data-category="{{ $category }}" class="chip">{{ $category }}</button>
                @endforeach
            </div>

            <div id="product-grid" class="product-grid">
                @forelse ($products as $product)
                    @php($availableStock = (int) ($product->available_stock ?? 0))
                    <article
                        class="product-card"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}"
                        data-product-price="{{ $product->price }}"
                        data-product-category="{{ $product->category }}"
                    >
                        <div class="product-card__media">
                            <svg class="product-card__icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">{!! \App\Catalog\Catalog::ICONS[$product->icon] ?? '' !!}</svg>
                            <span class="product-card__tag">{{ $product->category }}</span>
                        </div>
                        <div class="product-card__body">
                            <h3 class="product-card__name">{{ $product->name }}</h3>
                            <p class="product-card__price">${{ number_format($product->price, 2) }}</p>
                            <button type="button" data-add-to-cart="{{ $product->id }}"
                                    @disabled($availableStock === 0)
                                    class="btn btn--dark btn--block btn--small product-card__cta">
                                {{ $availableStock === 0 ? 'Sin inventario' : 'Agregar al carrito' }}
                            </button>
                        </div>
                    </article>
                @empty
                    <p class="product-empty">No hay productos disponibles en este momento.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-storefront.layout>
