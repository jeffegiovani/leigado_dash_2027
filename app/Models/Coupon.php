<?php

namespace App\Models;

use App\Enums\ResourceVisibilityEnum;
use App\Models\Concerns\DeletesManagedImages;
use Database\Factories\CouponFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    use DeletesManagedImages;

    /** @use HasFactory<CouponFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupons';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'segments' => 'array',
        'visibility' => ResourceVisibilityEnum::class,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return array<int, string>
     */
    public function managedImageAttributes(): array
    {
        return ['avatar', 'cover'];
    }
}
