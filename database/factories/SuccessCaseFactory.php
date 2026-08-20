<?php

namespace Database\Factories;

use App\Enums\ResourceVisibilityEnum;
use App\Models\SuccessCase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SuccessCase>
 */
class SuccessCaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);

        return [
            'segments' => [],
            'visibility' => ResourceVisibilityEnum::Public,
            'avatar' => null,
            'name' => fake()->name(),
            'job_position' => fake()->jobTitle(),
            'testimony' => fake()->sentence(),
            'logotype' => null,
            'customer_name' => fake()->company(),
            'customer_location' => fake()->city(),
            'cover' => null,
            'title' => Str::limit($title, 120, ''),
            'slug' => Str::limit(Str::slug($title), 125, ''),
            'embed_video' => null,
            'cta' => fake()->sentence(),
            'content' => '<p>'.fake()->paragraph().'</p>',
        ];
    }
}
