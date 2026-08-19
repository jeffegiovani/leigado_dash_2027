<?php

namespace App\Models;

use App\Enums\ResourceVisibilityEnum;
use Database\Factories\FaqFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    /** @use HasFactory<FaqFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faqs';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'visibility' => ResourceVisibilityEnum::class,
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(FaqGroup::class, 'group_id');
    }
}
