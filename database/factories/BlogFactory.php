<?php

namespace Database\Factories;

use App\Enums\ResourceVisibilityEnum;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);

        return [
            'author_id' => User::factory(),
            'visibility' => ResourceVisibilityEnum::Public,
            'thumb' => null,
            'cover' => null,
            'title' => Str::limit($title, 120, ''),
            'slug' => Str::limit(Str::slug($title), 125, ''),
            'cta' => fake()->sentence(),
            'content' => '<p>'.fake()->paragraph().'</p>',
        ];
    }
}
