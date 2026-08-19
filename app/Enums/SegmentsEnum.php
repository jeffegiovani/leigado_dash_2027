<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SegmentsEnum: string implements HasColor, HasIcon, HasLabel
{
    case Leite = 'leite';
    case Leche = 'leche';
    case Milk = 'milk';
    case Corte = 'corte';
    case Vet = 'vet';
    case Laticinio = 'laticinio';

    public function getLabel(): string
    {
        return match ($this) {
            self::Leite => 'Leite',
            self::Leche => 'Leche',
            self::Milk => 'Milk',
            self::Corte => 'Corte',
            self::Vet => 'Veterinários',
            self::Laticinio => 'Laticínios',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Leite => 'primary',
            self::Leche => 'primary',
            self::Milk => 'primary',
            self::Corte => 'gray',
            self::Vet => 'info',
            self::Laticinio => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Leite => 'heroicon-o-check-badge',
            self::Leche => 'heroicon-o-check-badge',
            self::Milk => 'heroicon-o-check-badge',
            self::Corte => 'heroicon-o-check-badge',
            self::Vet => 'heroicon-o-check-badge',
            self::Laticinio => 'heroicon-o-check-badge',
        };
    }
}
