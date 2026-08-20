<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AttendantSegmentEnum: string implements HasLabel
{
    case General = 'general';
    case Dairy = 'dairy';

    public function getLabel(): string
    {
        return match ($this) {
            self::General => 'Geral',
            self::Dairy => 'Laticínios',
        };
    }

    /**
     * Explicação exibida no formulário do painel.
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::General => 'Exibido nas páginas gerais do site',
            self::Dairy => 'Exibido nas páginas de laticínios, PQFL e PMLS',
        };
    }

    /**
     * Segmentos aplicados a quem foi cadastrado antes do campo existir.
     *
     * @return array<int, string>
     */
    public static function defaults(): array
    {
        return [self::General->value];
    }
}
