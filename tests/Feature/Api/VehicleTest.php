<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domains\User\Models\User;
use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Vehicle\Models\VehicleType;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ApiTestCase;

class VehicleTest extends ApiTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
    }

    public function test_admin_can_list_vehicles(): void
    {
        Vehicle::create([
            'plate' => 'AAA-001', 'brand' => 'Toyota', 'model' => 'Hilux',
            'year' => 2020, 'status' => 'Activa',
        ]);
        Vehicle::create([
            'plate' => 'BBB-002', 'brand' => 'Nissan', 'model' => 'NP300',
            'year' => 2021, 'status' => 'Activa',
        ]);
        Vehicle::create([
            'plate' => 'CCC-003', 'brand' => 'Ford', 'model' => 'Ranger',
            'year' => 2022, 'status' => 'Activa',
        ]);

        $admin = User::create([
            'name' => 'Admin', 'email' => 'admin@test.com',
            'password' => bcrypt('password'), 'role' => 'Administrador del sistema',
        ]);
        $admin->assignRole('Administrador del sistema');
        $token = $admin->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/vehicles');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta']);
    }

    public function test_admin_can_create_vehicle(): void
    {
        VehicleType::create(['name' => 'Camioneta']);

        $admin = User::create([
            'name' => 'Admin', 'email' => 'admin@test.com',
            'password' => bcrypt('password'), 'role' => 'Administrador del sistema',
        ]);
        $admin->assignRole('Administrador del sistema');
        $token = $admin->createToken('test')->plainTextToken;

        $data = [
            'plate' => 'ABC-1234',
            'brand' => 'Toyota',
            'model' => 'Hilux',
            'year' => 2020,
            'vehicle_type' => 1,
        ];

        $response = $this->withToken($token)->postJson('/api/vehicles', $data);

        $response->assertStatus(201);
    }

    public function test_admin_can_show_vehicle(): void
    {
        $vehicle = Vehicle::create([
            'plate' => 'XYZ-9876', 'brand' => 'Nissan', 'model' => 'NP300',
            'year' => 2021, 'status' => 'Activa',
        ]);

        $admin = User::create([
            'name' => 'Admin', 'email' => 'admin@test.com',
            'password' => bcrypt('password'), 'role' => 'Administrador del sistema',
        ]);
        $admin->assignRole('Administrador del sistema');
        $token = $admin->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->getJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.plate', 'XYZ-9876');
    }

    public function test_admin_can_update_vehicle(): void
    {
        $vehicle = Vehicle::create([
            'plate' => 'LMN-4567', 'brand' => 'Mazda', 'model' => 'BT-50',
            'year' => 2022, 'status' => 'Activa',
        ]);

        $admin = User::create([
            'name' => 'Admin', 'email' => 'admin@test.com',
            'password' => bcrypt('password'), 'role' => 'Administrador del sistema',
        ]);
        $admin->assignRole('Administrador del sistema');
        $token = $admin->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->putJson("/api/vehicles/{$vehicle->id}", [
            'brand' => 'Mazda Updated',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.brand', 'Mazda Updated');
    }

    public function test_admin_can_delete_vehicle(): void
    {
        $vehicle = Vehicle::create([
            'plate' => 'QRS-7890', 'brand' => 'Ford', 'model' => 'Ranger',
            'year' => 2023, 'status' => 'Activa',
        ]);

        $admin = User::create([
            'name' => 'Admin', 'email' => 'admin@test.com',
            'password' => bcrypt('password'), 'role' => 'Administrador del sistema',
        ]);
        $admin->assignRole('Administrador del sistema');
        $token = $admin->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->deleteJson("/api/vehicles/{$vehicle->id}");

        $response->assertStatus(204);
    }

    public function test_user_without_permission_cannot_list_vehicles(): void
    {
        $user = User::create([
            'name' => 'Chofer', 'email' => 'chofer@test.com',
            'password' => bcrypt('password'), 'role' => 'Chofer',
        ]);
        $user->assignRole('Chofer');
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/vehicles');

        $response->assertStatus(403);
    }
}
