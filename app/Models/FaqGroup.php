<?php

namespace App\Models;

use Database\Factories\FaqGroupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaqGroup extends Model
{
    /** @use HasFactory<FaqGroupFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faq_groups';

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class, 'group_id', 'id');
    }
}
