<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicles_endpoint_returns_data_envelope(): void
    {
        $response = $this->getJson('/api/vehicles');
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_vehicles_endpoint_returns_200_when_no_vehicles(): void
    {
        $response = $this->getJson('/api/vehicles');
        $response->assertOk();
        $this->assertIsArray($response->json('data'));
    }
}