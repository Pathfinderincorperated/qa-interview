<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DataEntry;
use App\Models\User;
use Illuminate\Database\Seeder;

class DataBankSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Sales', 'Support', 'Billing'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
        $categoryIds = Category::pluck('id', 'name')->all();

        $user1 = User::first();
        if (! $user1) {
            return;
        }

        // Entries WITH a category (Sales, Support, Billing)
        foreach (['Sales', 'Support', 'Billing'] as $name) {
            for ($i = 1; $i <= 5; $i++) {
                DataEntry::create([
                    'category_id' => $categoryIds[$name],
                    'user_id' => $user1->id,
                    'title' => "{$name} entry {$i}",
                    'value' => "value-{$i}",
                ]);
            }
        }

        // Entries with NO category (for QA: landing page says "all have a category")
        DataEntry::create([
            'category_id' => null,
            'user_id' => $user1->id,
            'title' => 'Uncategorized item A',
            'value' => 'value-a',
        ]);
        DataEntry::create([
            'category_id' => null,
            'user_id' => $user1->id,
            'title' => 'Uncategorized item B',
            'value' => 'value-b',
        ]);
    }
}
