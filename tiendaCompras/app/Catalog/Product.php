<?php

namespace App\Catalog;

use JsonSerializable;

/**
 * Un producto del catálogo de la tienda.
 */
final readonly class Product implements JsonSerializable
{
    /**
     * @param  int  $id  Identificador único del producto.
     * @param  string  $name  Nombre comercial.
     * @param  float  $price  Precio en dólares.
     * @param  string  $category  Categoría a la que pertenece.
     * @param  string  $icon  Clave del icono asociado.
     * @param  string  $slug  Identificador legible para URLs.
     */
    public function __construct(
        public int $id,
        public string $name,
        public float $price,
        public string $category,
        public string $icon,
        public string $slug,
    ) {}

    /**
     * Get the product as a JSON serializable array.
     *
     * @return array{id: int, name: string, price: float, category: string, icon: string, slug: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'category' => $this->category,
            'icon' => $this->icon,
            'slug' => $this->slug,
        ];
    }
}
