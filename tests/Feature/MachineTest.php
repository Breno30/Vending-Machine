<?php

namespace Tests\Feature;

use App\Models\Machine;
use Illuminate\Http\Response;
use Tests\TestCase;

class MachineTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testIndex(): void
    {
        $response = $this->get('/api/machines');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'latitude',
                    'longitude',
                    'owner_id',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }

    public function testStore()
    {
        $machineData = Machine::factory()->make()->toArray();

        $response = $this->post('api/machines', $machineData);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                'latitude',
                'longitude',
                'owner_id',
                'updated_at',
                'created_at',
                'id'
            ]
        ]);
    }

    public function testShow()
    {
        $machine = Machine::factory()->create();

        $response = $this->get('api/machines/' . $machine->id);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => [
                'latitude',
                'longitude',
                'owner_id',
                'updated_at',
                'created_at'
            ]
        ]);
    }

    public function testUpdate()
    {
        $machine = Machine::factory()->create();

        $machineData = [
            'latitude' => 10,
            'longitude' => 40
        ];

        $response = $this->put('api/machines/' . $machine->id, $machineData);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'data' => [
                'id' => $machine->id,
                'latitude' => 10,
                'longitude' => 40
            ]
        ]);
    }

    public function  testDestroy()
    {
        $machine = Machine::factory()->create();

        $response = $this->delete('api/machines/' . $machine->id);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'message' => 'Machine Deleted'
        ]);
    }
}
