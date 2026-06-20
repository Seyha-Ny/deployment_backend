<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products(): void
    {
        $category = Category::factory()->create();
        Product::factory(3)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'category_id', 'name', 'slug', 'description', 'price', 'stock', 'created_at', 'updated_at'],
                ],
            ]);
    }

    public function test_can_create_product(): void
    {
        $category = Category::factory()->create();

        $payload = [
            'category_id' => $category->id,
            'name' => 'Smartphone',
            'description' => 'A high-end smartphone',
            'price' => 699.99,
            'stock' => 50,
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Product created successfully',
            ])
            ->assertJsonStructure([
                'data' => ['id', 'category_id', 'name', 'slug', 'price', 'stock'],
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Smartphone',
            'price' => 699.99,
        ]);
    }

    public function test_can_show_product(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                ],
            ]);
    }

    public function test_can_update_product(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product',
            'price' => 99.99,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product updated successfully',
                'data' => ['name' => 'Updated Product', 'price' => 99.99],
            ]);
    }

    public function test_can_delete_product(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product deleted successfully',
            ]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_validation_errors_on_create(): void
    {
        $response = $this->postJson('/api/products', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id', 'name', 'price']);
    }
}
