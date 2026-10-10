@props(['active' => null])

<header class="site-header">
    <div class="container site-header__inner">
        <a href="{{ route('home') }}" class="brand">
            AZUL<span class="brand__dot">.</span>
        </a>

        <nav aria-label="Principal" class="site-nav">
            @foreach ([
                'home' => 'Inicio',
                'products' => 'Productos',
                'stores' => 'Sucursales',
                'about' => 'Nosotros',
                'contact' => 'Contacto',
            ] as $route => $label)
                <a
                    href="{{ route($route) }}"
                    @class(['site-nav__link', 'site-nav__link--active' => $active === $route])
                    @if ($active === $route) aria-current="page" @endif
                >{{ $label }}</a>
            @endforeach
        </nav>

        <div class="site-header__actions">
            <button id="cart-button" type="button" aria-label="Abrir carrito" class="cart-toggle">
                <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 3h2l2.2 12.4a1.5 1.5 0 0 0 1.5 1.1h8.6a1.5 1.5 0 0 0 1.5-1.2L20.5 7H6"/><circle cx="9.5" cy="20" r="1"/><circle cx="16.5" cy="20" r="1"/></svg>
                <span id="cart-badge" class="cart-badge"></span>
            </button>
        </div>
    </div>
</header>
