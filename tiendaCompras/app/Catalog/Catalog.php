<?php

namespace App\Catalog;

/**
 * Fuente única de datos del catálogo y las sucursales.
 *
 * La tienda es de solo lectura y el catálogo no se persiste, por lo que se
 * define como constantes en lugar de tocar la base de datos.
 */
final class Catalog
{
    /**
     * @var list<Product>
     */
    public const PRODUCTS = [
        1 => ['Laptop Aura Pro 14"', 1299.0, 'Computación', 'laptop', 'laptop-aura-pro-14'],
        2 => ['Teclado mecánico Táctil 65%', 89.0, 'Computación', 'keyboard', 'teclado-mecanico-tactil-65'],
        3 => ['Mouse inalámbrico Glide MX', 45.0, 'Computación', 'mouse', 'mouse-inalambrico-glide-mx'],
        4 => ['Audífonos Sono Quiet ANC', 199.0, 'Audio', 'headphones', 'audifonos-sono-quiet-anc'],
        5 => ['Parlante Pulse Mini', 59.0, 'Audio', 'speaker', 'parlante-pulse-mini'],
        6 => ['Smartwatch Cronos S', 149.0, 'Wearables', 'watch', 'smartwatch-cronos-s'],
        7 => ['Teléfono Nova X5', 599.0, 'Móviles', 'phone', 'telefono-nova-x5'],
        8 => ['Cámara Visor One 4K', 749.0, 'Fotografía', 'camera', 'camara-visor-one-4k'],
    ];

    /**
     * @var list<Store>
     */
    public const STORES = [
        ['AZUL Las Mercedes', 'Av. Principal de Las Mercedes, C.C. Azul, Nivel 1, Local 12',
            ['Las Mercedes', 'El Rosal', 'Los Palos Grandes'], '584120000001', 'https://maps.google.com/?q=Las+Mercedes'],
        ['AZUL La Candelaria', 'Av. Urdaneta, Edificio Azul Center, Planta Baja',
            ['Centro', 'Altagracia', 'La Pastora'], '584120000002', 'https://maps.google.com/?q=La+Candelaria'],
        ['AZUL Millenium', 'C.C. Millenium Mall, Nivel Feria, Local 25',
            ['Los Dos Caminos', 'Petare', 'La California'], '584120000003', 'https://maps.google.com/?q=Millenium+Mall'],
        ['AZUL Barquisimeto', 'Av. Venezuela entre Av. Argimiro y Av. Los Leones, C.C. Los Próceres',
            ['Zona Centro', 'Zona Este', 'Zona Norte'], '584120000004', 'https://maps.google.com/?q=Barquisimeto'],
    ];

    /**
     * @var array<string, string> Iconos SVG por clave de categoría.
     */
    public const ICONS = [
        'laptop' => '<path d="M6 5.5h12v10H6z"/><path d="M3.5 18.5h17"/>',
        'keyboard' => '<rect x="3" y="7" width="18" height="10" rx="1.5"/><path d="M7 11h.01M11 11h.01M15 11h.01M8 14h8"/>',
        'mouse' => '<rect x="7" y="3.5" width="10" height="17" rx="5"/><path d="M12 7v3"/>',
        'headphones' => '<path d="M4 13c0-4.4 3.6-8 8-8s8 3.6 8 8"/><rect x="3" y="13" width="4" height="6" rx="1.5"/><rect x="17" y="13" width="4" height="6" rx="1.5"/>',
        'speaker' => '<rect x="6" y="3.5" width="12" height="17" rx="2"/><circle cx="12" cy="14" r="3"/><circle cx="12" cy="7.5" r="1"/>',
        'watch' => '<circle cx="12" cy="12" r="4.5"/><path d="M9 4.5h6l.5 3M15 19.5H9l-.5-3"/>',
        'phone' => '<rect x="7" y="3" width="10" height="18" rx="2.5"/><path d="M11 17.5h2"/>',
        'camera' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="m9 7 1.2-2.5h3.6L15 7"/><circle cx="12" cy="13" r="3.5"/>',
    ];

    /**
     * Get every product in the catalog.
     *
     * @return list<Product>
     */
    public static function products(): array
    {
        return array_map(
            fn (int $id, array $data): Product => new Product(
                id: $id,
                name: $data[0],
                price: $data[1],
                category: $data[2],
                icon: $data[3],
                slug: $data[4],
            ),
            array_keys(self::PRODUCTS),
            self::PRODUCTS,
        );
    }

    /**
     * Get the distinct categories present in the catalog.
     *
     * @return list<string>
     */
    public static function categories(): array
    {
        return array_values(array_unique(array_column(self::PRODUCTS, 2)));
    }

    /**
     * Get every store location.
     *
     * @return list<Store>
     */
    public static function stores(): array
    {
        return array_map(
            fn (array $data): Store => new Store(
                name: $data[0],
                address: $data[1],
                zones: $data[2],
                whatsapp: $data[3],
                mapUrl: $data[4],
            ),
            self::STORES,
        );
    }
}
