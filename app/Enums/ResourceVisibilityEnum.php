<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ResourceVisibilityEnum: string implements HasColor, HasIcon, HasLabel
{
    case Private = 'private';
    case Unlisted = 'unlisted'; // Qualquer pessoa com o link pode acessar
    case Public = 'public'; // Exibido nos sites

    public function getLabel(): string
    {
        return match ($this) {
            self::Private => 'Privado',
            self::Unlisted => 'Não listado',
            self::Public => 'Público',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Private => 'danger',
            self::Unlisted => 'gray',
            self::Public => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Private => 'heroicon-o-no-symbol',
            self::Unlisted => 'heroicon-o-link',
            self::Public => 'heroicon-o-eye',
        };
    }
}
