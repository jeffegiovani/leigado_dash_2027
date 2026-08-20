<?php

namespace Database\Factories;

use App\Enums\ResourceVisibilityEnum;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
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
            'author_id' => User::factory(),
            'segments' => [],
            'visibility' => ResourceVisibilityEnum::Public,
            'avatar' => 'coupons/avatars/'.Str::ulid().'.webp',
            'cover' => null,
            'partner' => fake()->company(),
            'title' => Str::limit($title, 120, ''),
            'slug' => Str::limit(Str::slug($title), 125, ''),
            'offer_headline' => '10% OFF',
            'cta' => fake()->sentence(),
            'content' => '<p>'.fake()->paragraph().'</p>',
        ];
    }
}
