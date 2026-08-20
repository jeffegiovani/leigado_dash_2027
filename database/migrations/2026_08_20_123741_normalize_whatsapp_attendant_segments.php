<?php

use App\Enums\AttendantSegmentEnum;
use App\Enums\SiteConfigKeyEnum;
use App\Models\SiteConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Mesmo diretório usado pelo upload de avatares no painel.
     */
    protected const AVATAR_DIRECTORY = 'site-configs/attendants';

    /**
     * Troca o booleano `is_dairy_attendant` pela lista `segments` nos atendentes
     * já gravados, para que o portal continue encontrando quem exibir.
     *
     * Registros duplicados (o mesmo telefone cadastrado duas vezes só para
     * aparecer nos dois contextos) viram um único atendente com os dois
     * segmentos — o `down()` os separa de novo.
     *
     * Avatares legados (`volmir.webp`, servidos pelos estáticos do portal)
     * passam a apontar para o storage deste painel, que é a única fonte depois
     * da remoção do fallback no portal.
     */
    public function up(): void
    {
        $attendants = SiteConfig::valueFor(SiteConfigKeyEnum::WhatsappAttendants);

        if (! is_array($attendants) || $attendants === []) {
            return;
        }

        SiteConfig::store(
            SiteConfigKeyEnum::WhatsappAttendants,
            $this->withStorageAvatars(
                SiteConfig::mergeDuplicatedAttendants(
                    SiteConfig::normalizeAttendantSegments($attendants)
                )
            ),
        );
    }

    /**
     * Prefixa avatares legados com o diretório usado pelo upload do painel.
     *
     * @param  array<int, array<string, mixed>>  $attendants
     * @return array<int, array<string, mixed>>
     */
    protected function withStorageAvatars(array $attendants): array
    {
        return collect($attendants)
            ->map(function (array $attendant): array {
                $avatar = $attendant['avatar'] ?? null;

                if (! is_string($avatar) || $avatar === '' || str_contains($avatar, '/')) {
                    return $attendant;
                }

                $path = self::AVATAR_DIRECTORY.'/'.$avatar;

                if (Storage::disk('public')->missing($path)) {
                    return $attendant;
                }

                $attendant['avatar'] = $path;

                return $attendant;
            })
            ->all();
    }

    public function down(): void
    {
        $attendants = SiteConfig::valueFor(SiteConfigKeyEnum::WhatsappAttendants);

        if (! is_array($attendants) || $attendants === []) {
            return;
        }

        $restored = collect($attendants)
            ->flatMap(function (array $attendant): array {
                $segments = (array) ($attendant['segments'] ?? []);

                unset($attendant['segments']);

                // O formato antigo era exclusivo: quem aparecia nos dois
                // contextos precisava de uma entrada para cada um.
                return collect($segments)
                    ->map(fn (string $segment): array => [
                        ...$attendant,
                        'is_dairy_attendant' => $segment === AttendantSegmentEnum::Dairy->value,
                    ])
                    ->all();
            })
            ->values()
            ->all();

        SiteConfig::store(SiteConfigKeyEnum::WhatsappAttendants, $restored);
    }
};
