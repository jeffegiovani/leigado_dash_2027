<?php

namespace App\Models;

use App\Enums\SiteConfigKeyEnum;
use Database\Factories\SiteConfigFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    /** @use HasFactory<SiteConfigFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'site_configs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
        'info',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'key' => SiteConfigKeyEnum::class,
        'value' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Valor gravado para a chave informada, considerando apenas registros ativos.
     */
    public static function valueFor(SiteConfigKeyEnum $key, mixed $default = null): mixed
    {
        $config = static::query()
            ->where('key', $key->value)
            ->where('is_active', true)
            ->first();

        return $config?->value ?? $default;
    }

    /**
     * Grava (criando ou atualizando) o valor da chave informada.
     */
    public static function store(SiteConfigKeyEnum $key, mixed $value, bool $isActive = true): static
    {
        return static::query()->updateOrCreate(
            ['key' => $key->value],
            [
                'value' => $value,
                'info' => $key->getInfo(),
                'is_active' => $isActive,
            ],
        );
    }
}
