<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $categories = Category::factory(6)->create();

        $categories->each(function (Category $category) {
            Product::factory(rand(3, 8))->create([
                'category_id' => $category->id,
            ]);
        });
    }
}
