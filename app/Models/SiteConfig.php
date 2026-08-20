<?php

namespace App\Models;

use App\Enums\AttendantSegmentEnum;
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
     * Converte atendentes gravados antes do campo `segments` existir.
     *
     * O antigo `is_dairy_attendant` era exclusivo: quem tinha a flag aparecia
     * somente nas páginas de laticínios, e quem não tinha, somente nas demais.
     *
     * @param  mixed  $attendants
     * @return array<int, array<string, mixed>>
     */
    public static function normalizeAttendantSegments($attendants): array
    {
        if (! is_array($attendants)) {
            return [];
        }

        return collect($attendants)
            ->filter(fn ($attendant): bool => is_array($attendant))
            ->map(function (array $attendant): array {
                if (filled($attendant['segments'] ?? null)) {
                    unset($attendant['is_dairy_attendant']);

                    return $attendant;
                }

                $attendant['segments'] = ($attendant['is_dairy_attendant'] ?? false)
                    ? [AttendantSegmentEnum::Dairy->value]
                    : [AttendantSegmentEnum::General->value];

                unset($attendant['is_dairy_attendant']);

                return $attendant;
            })
            ->values()
            ->all();
    }

    /**
     * Funde registros repetidos do mesmo telefone somando seus segmentos.
     *
     * O formato antigo exigia duas entradas para um atendente aparecer nas
     * páginas gerais e nas de laticínios; com `segments` uma basta.
     *
     * Chamado apenas pela migração que introduziu `segments`: no uso normal do
     * painel dois cadastros com o mesmo telefone são intencionais.
     *
     * @param  array<int, array<string, mixed>>  $attendants
     * @return array<int, array<string, mixed>>
     */
    public static function mergeDuplicatedAttendants(array $attendants): array
    {
        return collect($attendants)
            ->groupBy(fn (array $attendant): string => (string) ($attendant['phone'] ?? ''))
            ->map(function ($group): array {
                $merged = $group->first();

                $merged['segments'] = $group
                    ->flatMap(fn (array $attendant): array => $attendant['segments'])
                    ->unique()
                    ->values()
                    ->all();

                return $merged;
            })
            ->values()
            ->all();
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
