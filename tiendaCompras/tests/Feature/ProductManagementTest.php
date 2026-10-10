<?php

use App\Models\Product;
use App\Models\User;

it('lists every product for authenticated users', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create(['name' => 'Laptop Aura Pro']);

    $this->actingAs($user)
        ->get(route('products.index'))
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee($product->sku);
});

it('redirects guests to the login page', function (): void {
    $this->get(route('products.index'))->assertRedirect(route('login'));
});

it('creates a product with a generated slug and icon', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('products.store'), [
            'name' => 'Monitor Gamer 27"',
            'sku' => 'AZUL-MON-009',
            'category' => 'Computación',
            'price' => '349.50',
            'description' => 'Panel IPS de 165 Hz.',
            'is_active' => '1',
        ])
        ->assertRedirect(route('products.index'));

    $product = Product::where('sku', 'AZUL-MON-009')->firstOrFail();

    expect($product->slug)->toBe('monitor-gamer-27')
        ->and($product->icon)->toBe('laptop')
        ->and($product->is_active)->toBeTrue();
});

it('rejects a duplicate sku', function (): void {
    $user = User::factory()->create();
    Product::factory()->create(['sku' => 'AZUL-DUP-001']);

    $this->actingAs($user)
        ->from(route('products.create'))
        ->post(route('products.store'), [
            'name' => 'Producto duplicado',
            'sku' => 'AZUL-DUP-001',
            'category' => 'Audio',
            'price' => '10.00',
        ])
        ->assertSessionHasErrors('sku');

    expect(Product::where('sku', 'AZUL-DUP-001')->count())->toBe(1);
});

it('updates an existing product', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create(['name' => 'Nombre viejo']);

    $this->actingAs($user)
        ->put(route('products.update', $product), [
            'name' => 'Nombre nuevo',
            'sku' => $product->sku,
            'category' => $product->category,
            'price' => '99.99',
            'is_active' => '1',
        ])
        ->assertRedirect(route('products.index'));

    expect($product->fresh())
        ->name->toBe('Nombre nuevo')
        ->price->toBe('99.99');
});

it('deletes a product', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->delete(route('products.destroy', $product))
        ->assertRedirect(route('products.index'));

    expect(Product::find($product->id))->toBeNull();
});

it('shows only active products in the public storefront', function (): void {
    Product::factory()->create(['name' => 'Visible en tienda', 'is_active' => true]);
    Product::factory()->create(['name' => 'Oculto en tienda', 'is_active' => false]);

    $this->get(route('catalog'))
        ->assertOk()
        ->assertSee('Visible en tienda')
        ->assertDontSee('Oculto en tienda');
});

it('derives storefront availability from the product batches', function (): void {
    $inStock = Product::factory()->create(['name' => 'Con existencias']);
    $inStock->batches()->create(['quantity' => 5]);

    Product::factory()->create(['name' => 'Agotado']);

    $this->get(route('catalog'))
        ->assertOk()
        ->assertSee('Con existencias')
        ->assertSee('Agotado')
        ->assertSee('Agregar al carrito')
        ->assertSee('Sin inventario');
});
