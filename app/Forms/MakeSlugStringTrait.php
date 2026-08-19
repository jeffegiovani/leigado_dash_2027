<?php

namespace App\Forms;

// use Illuminate\Database\Eloquent\Model;

trait MakeSlugStringTrait
{
    /**
     * Generate a URL-friendly "slug" from a given string.
     *
     * @param  string  $modelFullyClassName  -> exemplo: App\Models\Blog
     */
    public static function makeSlugString(string $fullTitle, string $modelFullyClassName, ?string $slug = null, ?int $limitChars = 120, ?string $slugColumnName = 'slug', ?int $currentRecordId = 0): string
    {
        $slug ??= $fullTitle;

        $slug = str($slug)
            ->slug('-', 'pt_BR', ['@' => '-'])
            ->limit($limitChars, '');

        $modelInstance = app($modelFullyClassName);

        $checkIsUniqueSlug = $modelInstance::query()
            ->where($slugColumnName, $slug)
            ->when($currentRecordId > 0, fn ($query) => $query->where('id', '!=', $currentRecordId))
            ->get();

        if ($checkIsUniqueSlug->isNotEmpty()) {
            $slug .= '-'.uniqid();
        }

        return $slug;
    }
}
