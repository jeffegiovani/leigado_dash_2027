<?php

namespace App\Models;

use App\Enums\ResourceVisibilityEnum;
use App\Models\Concerns\DeletesManagedImages;
use Database\Factories\BlogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Blog extends Model
{
    use DeletesManagedImages;

    /** @use HasFactory<BlogFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blogs';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // 'cover' => 'array',
        'visibility' => ResourceVisibilityEnum::class,
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_has_category', 'article_id', 'category_id');
    }

    /**
     * @return array<int, string>
     */
    public function managedImageAttributes(): array
    {
        return ['thumb', 'cover'];
    }
}
