<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex(): void
    {
        $response = $this->get('/api/products');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'price',
                    'description',
                    'image',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function testStore()
    {
        $productData = Product::factory()->make()->toArray();

        $response = $this->post('/api/products', $productData);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJsonStructure([
            'data' => [
                'name',
                'description',
                'price',
                'updated_at',
                'created_at',
                'id'
            ]
        ]);
    }

    public function testShow()
    {
        $product = Product::factory()->create();

        $response = $this->get('api/products/' . $product->id);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                'name',
                'description',
                'price',
                'updated_at',
                'created_at',
                'id'
            ]
        ]);
    }

    public function testUpdate()
    {
        $product = Product::factory()->create();

        $productData = [
            'name' => 'new Name'
        ];

        $response = $this->put('/api/products/' . $product->id, $productData);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                'name',
                'description',
                'price',
                'updated_at',
                'created_at',
                'id'
            ]
        ]);

        $this->assertTrue($response['data']['name'] == 'new Name');
    }

    public function testDestroy() {
        $product = Product::factory()->create();

        $response = $this->delete('/api/products/'. $product->id);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'message' => 'Product deleted'
        ]);
    }
}
