<?php

namespace Database\Factories;

use App\Enums\SiteConfigKeyEnum;
use App\Models\SiteConfig;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteConfig>
 */
class SiteConfigFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $key = fake()->randomElement(SiteConfigKeyEnum::cases());

        return [
            'key' => $key->value,
            'value' => fake()->sentence(),
            'info' => $key->getInfo(),
            'is_active' => true,
        ];
    }
}
