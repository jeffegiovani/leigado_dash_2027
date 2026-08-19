<?php

namespace App\Models;

use App\Enums\ResourceVisibilityEnum;
use Database\Factories\SuccessCaseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessCase extends Model
{
    /** @use HasFactory<SuccessCaseFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'success_cases';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'segments' => 'array',
        'visibility' => ResourceVisibilityEnum::class,
    ];
}
