<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the store catalog.
     */
    public function run(): void
    {
        $products = [
            ['Laptop Aura Pro 14"', 'AZUL-LAP-001', 'Computación', 1299.00, 12, 'laptop', 'laptop-aura-pro-14', 'Pantalla de 14 pulgadas, 16 GB de RAM y SSD de 512 GB.'],
            ['Teclado mecánico Táctil 65%', 'AZUL-TEC-002', 'Computación', 89.00, 40, 'keyboard', 'teclado-mecanico-tactil-65', 'Formato compacto 65% con switches mecánicos yconnection USB-C.'],
            ['Mouse inalámbrico Glide MX', 'AZUL-MOU-003', 'Computación', 45.00, 55, 'mouse', 'mouse-inalambrico-glide-mx', 'Sensor de 4000 DPI y batería recargable de larga duración.'],
            ['Audífonos Sono Quiet ANC', 'AZUL-AUD-004', 'Audio', 199.00, 25, 'headphones', 'audifonos-sono-quiet-anc', 'Cancelación activa de ruido y 30 horas de reproducción.'],
            ['Parlante Pulse Mini', 'AZUL-AUD-005', 'Audio', 59.00, 60, 'speaker', 'parlante-pulse-mini', 'Compacto, resistente al agua y con 12 horas de batería.'],
            ['Smartwatch Cronos S', 'AZUL-WEA-006', 'Wearables', 149.00, 30, 'watch', 'smartwatch-cronos-s', 'Monitor de ritmo cardíaco, sueño y notificaciones.'],
            ['Teléfono Nova X5', 'AZUL-MOV-007', 'Móviles', 599.00, 18, 'phone', 'telefono-nova-x5', 'Pantalla de 6.5 pulgadas, 128 GB y cámaraTriple.'],
            ['Cámara Visor One 4K', 'AZUL-FOT-008', 'Fotografía', 749.00, 8, 'camera', 'camara-visor-one-4k', 'Video 4K, estabilizador óptico y zoom de 10x.'],
        ];

        foreach ($products as [$name, $sku, $category, $price, $stock, $icon, $slug, $description]) {
            $product = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name' => $name,
                    'category' => $category,
                    'price' => $price,
                    'icon' => $icon,
                    'slug' => $slug,
                    'description' => $description,
                    'is_active' => true,
                ],
            );

            if ($product->batches()->doesntExist()) {
                $product->batches()->create(['quantity' => $stock]);
            }
        }
    }
}
