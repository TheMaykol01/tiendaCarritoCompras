<?php

use App\Models\Batch;
use App\Models\InventoryLog;
use App\Models\User;

it('adds stock and records an entry movement', function (): void {
    $user = User::factory()->create();
    $batch = Batch::factory()->create(['quantity' => 10]);

    $this->actingAs($user)
        ->postJson(route('inventory.adjust'), [
            'barcode' => $batch->barcode,
            'type' => 'in',
            'quantity' => 5,
        ])
        ->assertOk()
        ->assertJson(['quantity' => 15]);

    expect($batch->fresh()->quantity)->toBe(15);

    $log = InventoryLog::firstOrFail();
    expect($log->type)->toBe('in')
        ->and($log->quantity)->toBe(5)
        ->and($log->batch_id)->toBe($batch->id);
});

it('removes stock and records an exit movement', function (): void {
    $user = User::factory()->create();
    $batch = Batch::factory()->create(['quantity' => 8]);

    $this->actingAs($user)
        ->postJson(route('inventory.adjust'), [
            'barcode' => $batch->barcode,
            'type' => 'out',
            'quantity' => 3,
        ])
        ->assertOk()
        ->assertJson(['quantity' => 5]);

    expect($batch->fresh()->quantity)->toBe(5);
});

it('refuses to remove more stock than available', function (): void {
    $user = User::factory()->create();
    $batch = Batch::factory()->create(['quantity' => 2]);

    $this->actingAs($user)
        ->postJson(route('inventory.adjust'), [
            'barcode' => $batch->barcode,
            'type' => 'out',
            'quantity' => 5,
        ])
        ->assertStatus(422);

    expect($batch->fresh()->quantity)->toBe(2)
        ->and(InventoryLog::count())->toBe(0);
});

it('rejects an unknown barcode', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('inventory.adjust'), [
            'barcode' => 'NO-EXISTE',
            'type' => 'in',
            'quantity' => 1,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('barcode');
});

it('requires authentication to adjust stock', function (): void {
    $batch = Batch::factory()->create();

    $this->postJson(route('inventory.adjust'), [
        'barcode' => $batch->barcode,
        'type' => 'in',
        'quantity' => 1,
    ])->assertUnauthorized();
});
