<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Categories with their related subcategories.
     *
     * @var array<string, list<string>>
     */
    private const CATEGORIES = [
        'Electrónica' => ['Teléfonos', 'Computadoras', 'Accesorios'],
        'Ropa' => ['Camisas', 'Pantalones', 'Zapatos'],
        'Hogar' => ['Cocina', 'Decoración'],
    ];

    /**
     * Seed the application's categories.
     */
    public function run(): void
    {
        foreach (self::CATEGORIES as $name => $children) {
            $parent = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'is_active' => true,
            ]);

            foreach ($children as $childName) {
                Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'description' => Str::headline("Subcategoría de {$name}"),
                    'parent_id' => $parent->id,
                    'is_active' => true,
                ]);
            }
        }
    }
}
