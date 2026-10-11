<?php

use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\User;

it('lists every product for authenticated users', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create(['name' => 'Laptop Aura Pro']);
    $product->batches()->create(['quantity' => 12]);

    $this->actingAs($user)
        ->get(route('products.index'))
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee($product->sku)
        ->assertSee('12 unidad(es)')
        ->assertSee('En stock')
        ->assertSee('+ Entrada')
        ->assertSee('name="quantity"', escape: false);
});

it('flags a product with few units on the product list', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $product->batches()->create(['quantity' => 3]);

    $this->actingAs($user)
        ->get(route('products.index'))
        ->assertOk()
        ->assertSee('3 unidad(es)')
        ->assertSee('Pocas existencias');
});

it('flags a product without units on the product list', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->get(route('products.index'))
        ->assertOk()
        ->assertSee('0 unidad(es)')
        ->assertSee('Sin existencias');
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
            'initial_quantity' => 12,
            'description' => 'Panel IPS de 165 Hz.',
            'is_active' => '1',
        ])
        ->assertRedirect(route('products.index'));

    $product = Product::where('sku', 'AZUL-MON-009')->firstOrFail();

    expect($product->slug)->toBe('monitor-gamer-27')
        ->and($product->icon)->toBe('laptop')
        ->and($product->is_active)->toBeTrue()
        ->and((int) $product->batches()->sum('quantity'))->toBe(12);
});

it('rejects a negative amount of units', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('products.create'))
        ->post(route('products.store'), [
            'name' => 'Mouse Gamer',
            'sku' => 'AZUL-MOU-010',
            'category' => 'Computación',
            'price' => '49.90',
            'initial_quantity' => -5,
        ])
        ->assertSessionHasErrors('initial_quantity');

    expect(Product::where('sku', 'AZUL-MOU-010')->exists())->toBeFalse();
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
            'initial_quantity' => 1,
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

it('shows how many units are available in the public storefront', function (): void {
    $pair = Product::factory()->create();
    $pair->batches()->create(['quantity' => 1]);

    $bulk = Product::factory()->create();
    $bulk->batches()->create(['quantity' => 12]);

    Product::factory()->create();

    $this->get(route('catalog'))
        ->assertOk()
        ->assertSee('1 unidad disponible')
        ->assertSee('12 unidades disponibles')
        ->assertSee('Sin unidades disponibles');
});

it('adds units from the product card and records the movement', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->from(route('products.index'))
        ->post(route('products.stock', $product), ['type' => 'in', 'quantity' => 6])
        ->assertRedirect(route('products.index'));

    expect((int) $product->batches()->sum('quantity'))->toBe(6);

    $log = InventoryLog::firstOrFail();
    expect($log->type)->toBe('in')
        ->and($log->quantity)->toBe(6);
});

it('refuses to remove more units than the product has', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $product->batches()->create(['quantity' => 2]);

    $this->actingAs($user)
        ->from(route('products.index'))
        ->post(route('products.stock', $product), ['type' => 'out', 'quantity' => 9])
        ->assertSessionHasErrors('quantity');

    expect((int) $product->batches()->sum('quantity'))->toBe(2)
        ->and(InventoryLog::count())->toBe(0);
});
