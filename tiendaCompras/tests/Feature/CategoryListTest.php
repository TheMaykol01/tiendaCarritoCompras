<?php

use App\Models\Category;
use App\Models\User;

it('lists root categories with their children for authenticated users', function (): void {
    $user = User::factory()->create();

    $parent = Category::factory()->create(['name' => 'Electrónica', 'slug' => 'electronica']);
    $child = Category::factory()->create(['name' => 'Teléfonos', 'slug' => 'telefonos', 'parent_id' => $parent->id]);

    $this->actingAs($user)
        ->get(route('categories.index'))
        ->assertOk()
        ->assertSee('Electrónica')
        ->assertSee('Teléfonos');
});

it('redirects guests to the login page', function (): void {
    $this->get(route('categories.index'))
        ->assertRedirect(route('login'));
});
