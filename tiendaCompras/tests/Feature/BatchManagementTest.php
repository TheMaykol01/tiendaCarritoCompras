<?php

use App\Models\Batch;
use App\Models\Product;
use App\Models\User;

it('lists the inventory lots for authenticated users', function (): void {
    $user = User::factory()->create();
    $batch = Batch::factory()->create();

    $this->actingAs($user)
        ->get(route('batches.index'))
        ->assertOk()
        ->assertSee($batch->batch_number)
        ->assertSee($batch->product->name);
});

it('redirects guests to the login page', function (): void {
    $this->get(route('batches.index'))->assertRedirect(route('login'));
});

it('registers a batch with a generated number and barcode', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->post(route('batches.store'), [
            'product_id' => $product->id,
            'expiry_date' => now()->addYear()->format('Y-m-d'),
            'quantity' => 25,
        ])
        ->assertRedirect(route('batches.index'));

    $batch = Batch::firstOrFail();

    expect($batch->product_id)->toBe($product->id)
        ->and($batch->quantity)->toBe(25)
        ->and($batch->batch_number)->toStartWith('LOTE-')
        ->and($batch->barcode)->toStartWith('AZ');
});

it('rejects an expiry date in the past', function (): void {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->from(route('batches.create'))
        ->post(route('batches.store'), [
            'product_id' => $product->id,
            'expiry_date' => now()->subDay()->format('Y-m-d'),
            'quantity' => 1,
        ])
        ->assertSessionHasErrors('expiry_date');

    expect(Batch::count())->toBe(0);
});

it('renders the barcode image for a batch', function (): void {
    $user = User::factory()->create();
    $batch = Batch::factory()->create();

    $this->actingAs($user)
        ->get(route('batches.barcode', $batch))
        ->assertOk()
        ->assertHeader('Content-Type', 'image/png');
});

it('deletes a batch', function (): void {
    $user = User::factory()->create();
    $batch = Batch::factory()->create();

    $this->actingAs($user)
        ->delete(route('batches.destroy', $batch))
        ->assertRedirect(route('batches.index'));

    expect(Batch::find($batch->id))->toBeNull();
});
