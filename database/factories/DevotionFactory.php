<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DevotionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'devotion_date' => fake()
                ->unique()
                ->dateTimeBetween('-1 year', '+1 year')
                ->format('Y-m-d'),

            'book' => 'Yohanes',
            'chapter' => 3,
            'verse_start' => 16,
            'verse_end' => null,
            'verse_text_en' => 'For God so loved the world',
            'verse_text_id' => 'Karena begitu besar kasih Allah',
            'devotion_title' => 'Kasih Allah',
            'devotion_text' => 'Renungan harian',
            'theme' => 'Kasih',
            'is_published' => true,
        ];
    }
}