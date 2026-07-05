<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_device_can_register(): void
    {
        $response = $this->postJson('/api/device/register', [
            'device_id' => fake()->uuid(),
            'push_token' => 'token-123',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('devices', [
            'push_token' => 'token-123',
        ]);
    }

    public function test_existing_device_updates_push_token(): void
    {
        $device = Device::factory()->create([
            'push_token' => 'old-token',
        ]);

        $this->postJson('/api/device/register', [
            'device_id' => $device->device_id,
            'push_token' => 'new-token',
        ]);

        $this->assertDatabaseHas('devices', [
            'device_id' => $device->device_id,
            'push_token' => 'new-token',
        ]);
    }

    public function test_device_ping_updates_last_seen_at(): void
    {
        $device = Device::factory()->create([
            'last_seen_at' => now()->subDay(),
        ]);

        $response = $this->postJson('/api/device/ping', [
            'device_id' => $device->device_id,
        ]);

        $response->assertStatus(200);

        $device->refresh();

        $this->assertTrue(
            $device->last_seen_at->gt(now()->subMinute())
        );
    }

    public function test_ping_returns_404_for_unknown_device(): void
    {
        $response = $this->postJson('/api/device/ping', [
            'device_id' => fake()->uuid(),
        ]);

        $response->assertStatus(404);
    }
}