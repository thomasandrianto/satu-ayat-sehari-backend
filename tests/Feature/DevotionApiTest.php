<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Devotion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DevotionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_today_devotion(): void
    {
        Devotion::factory()->create([
            'devotion_date' => today(),
            'devotion_title' => 'Kasih Allah',
            'is_published' => true,
        ]);

        $response = $this->getJson('/api/devotion/today');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Devotion found',
            ])
            ->assertJsonPath(
                'data.devotion_title',
                'Kasih Allah'
            );
    }

    public function test_today_returns_404_if_not_found(): void
    {
        $response = $this->getJson('/api/devotion/today');

        $response->assertStatus(404);
    }

    public function test_can_get_devotion_by_date(): void
    {
        Devotion::factory()->create([
            'devotion_date' => '2026-06-19',
            'devotion_title' => 'Iman',
            'is_published' => true,
        ]);

        $response = $this->getJson(
            '/api/devotion/2026-06-19'
        );

        $response
            ->assertStatus(200)
            ->assertJsonPath(
                'data.devotion_title',
                'Iman'
            );
    }

    public function test_unpublished_devotion_is_hidden(): void
    {
        Devotion::factory()->create([
            'devotion_date' => '2026-06-19',
            'is_published' => false,
        ]);

        $response = $this->getJson(
            '/api/devotion/2026-06-19'
        );

        $response->assertStatus(404);
    }

    public function test_can_get_devotions_archive(): void
    {
        Devotion::factory()->count(3)->create();

        $response = $this->getJson('/api/devotions');

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(3, 'data');
    }
}