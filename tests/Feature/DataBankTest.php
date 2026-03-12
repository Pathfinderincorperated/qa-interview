<?php

use App\Models\Category;
use App\Models\DataEntry;
use App\Models\User;

test('guests cannot access data bank', function () {
    $response = $this->get(route('data-bank.index'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can view data bank page', function () {
    $user = User::factory()->create();
    $category = Category::firstOrCreate(['name' => 'Test']);
    DataEntry::create([
        'category_id' => $category->id,
        'user_id' => $user->id,
        'title' => 'Test entry',
        'value' => 'test',
    ]);

    $this->actingAs($user);
    $response = $this->get(route('data-bank.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('DataBank/Index')
        ->has('entries')
        ->has('exportUrl')
    );
});

test('data bank export returns 403 for user without permission', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $this->actingAs($user);

    $response = $this->get(route('data-bank.export'));

    $response->assertForbidden();
});

test('data bank export succeeds for admin user', function () {
    $user = User::factory()->create(['is_admin' => true]);
    $category = Category::firstOrCreate(['name' => 'ExportTest']);
    DataEntry::create([
        'category_id' => $category->id,
        'user_id' => $user->id,
        'title' => 'One',
        'value' => 'v1',
    ]);

    $this->actingAs($user);
    $response = $this->get(route('data-bank.export'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
});
