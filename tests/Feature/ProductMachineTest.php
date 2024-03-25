<?php

namespace Tests\Feature;

use App\Models\Machine;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductMachineTest extends TestCase
{
    use RefreshDatabase;

    public function testProductToMachine(): void
    {
        $machine = Machine::factory()->create();
        $product = Product::factory()->create();
        $quantity = rand(1, 1000);

        $this->postProductToMachine($machine, $product, $quantity);

        $this->getProductsForMachine($machine, $product, $quantity);

        $this->removeProductFromMachine($machine, $product);
    }

    private function postProductToMachine(Machine $machine, Product $product, int $quantity)
    {
        $response = $this->post('api/machines/' . $machine->id . '/products', [
            'id' => $product->id,
            'quantity' => $quantity
        ]);

        $response->assertStatus(200);

        $response->assertExactJson([
            'message' => 'Product added to machine successfully'
        ]);
    }

    private function getProductsForMachine(Machine $machine, Product $product, int $quantity)
    {
        $response = $this->get('api/machines/' . $machine->id . '/products');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'data' => [
                [
                    'id' => 1,
                    'name' => $product->name,
                    'price' => $product->price,
                    'description' => $product->description,
                    'pivot' => [
                        'quantity' => $quantity,
                        'machine_id' => $machine->id,
                        'product_id' => $product->id
                    ]
                ]
            ]
        ]);
    }

    private function removeProductFromMachine(Machine $machine, Product $product)
    {
        $response = $this->delete('api/machines/' . $machine->id . '/products/' . $product->id);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertExactJson([
            'message' => 'Product removed from machine successfully'
        ]);
    }
}
