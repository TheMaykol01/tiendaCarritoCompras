# Tienda Carrito de Compras con Sistema

Tienda online con carrito de compras y sistema administrativo para el personal. Proyecto de portafolio.

> ⚠️ **Proyecto en desarrollo:** aún no verás el código perfeccionado, tenme paciencia.
> Vivo en Venezuela y todos los días me quitan la electricidad.

## Tecnologías

**Frontend:** Blade, CSS vanilla, JavaScript

**Backend:** PHP 8.2 con Laravel 12, Livewire/Volt, MySQL

## Estructura

```
└── tiendaCompras/            # Aplicación Laravel
    ├── app/
    │   ├── Catalog/           # Catálogo y sucursales (constantes PHP)
    │   └── Http/Controllers/  # StorefrontController
    ├── resources/
    │   ├── css/               # app.css (entrypoint), admin.css, storefront.css
    │   ├── js/app.js          # Carrito, filtros y búsqueda
    │   └── views/
    │       ├── components/storefront/  # Layout, header y footer del.store
    │       ├── livewire/               # Componentes Volt (auth, settings, categorías)
    │       └── storefront/             # Páginas públicas
    └── routes/web.php
```

## Páginas

| Ruta           | Descripción                     |
| -------------- | ------------------------------- |
| `/`            | Portada                         |
| `/productos`   | Catálogo con carrito             |
| `/sucursales`  | Sucursales y delivery           |
| `/nosotros`    | Misión y visión                 |
| `/contacto`    | Formulario de contacto          |
| `/dashboard`   | Panel (requiere autenticación)  |
| `/categories`  | Gestión de categorías           |

## Desarrollo

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Para los estilos: `npm run dev` (recarga) o `npm run build` (producción).
