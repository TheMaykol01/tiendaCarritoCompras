<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the categories used by the store catalog.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Computación',
                'slug' => 'computacion',
                'description' => 'Laptops, teclados y ratones para trabajar y estudiar.',
            ],
            [
                'name' => 'Audio',
                'slug' => 'audio',
                'description' => 'Audífonos y parlantes con cancelación de ruido.',
            ],
            [
                'name' => 'Wearables',
                'slug' => 'wearables',
                'description' => 'Relojes inteligentes y accesorios que te acompañan.',
            ],
            [
                'name' => 'Móviles',
                'slug' => 'moviles',
                'description' => 'Teléfonos de última generación con garantía local.',
            ],
            [
                'name' => 'Fotografía',
                'slug' => 'fotografia',
                'description' => 'Cámaras, lentes y accesorios para capturar cada momento.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'parent_id' => null,
                    'is_active' => true,
                ],
            );
        }
    }
}
