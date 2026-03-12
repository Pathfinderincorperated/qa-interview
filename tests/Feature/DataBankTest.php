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

test('data bank export succeeds for any authenticated user and returns empty csv', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $this->actingAs($user);

    $response = $this->get(route('data-bank.export'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    expect(strlen($response->getContent()))->toBe(0);
});
