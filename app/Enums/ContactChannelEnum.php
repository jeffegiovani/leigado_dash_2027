<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ContactChannelEnum: string implements HasColor, HasIcon, HasLabel
{
    case Contact = 'contact';
    case CallMe = 'callme';
    case SaleOrder = 'sale_order';
    case Referral = 'referral';
    case Coupon = 'coupon';
    case LandingPage = 'landing_page';

    public function getLabel(): string
    {
        return match ($this) {
            self::Contact => 'Contato',
            self::CallMe => 'Ligue Pra Mim',
            self::SaleOrder => 'Pedido de Venda',
            self::Referral => 'Indicação',
            self::Coupon => 'Cupom',
            self::LandingPage => 'Landing Page',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Contact => 'gray',
            self::CallMe => 'warning',
            self::SaleOrder => 'gray',
            self::Referral => 'gray',
            self::Coupon => 'gray',
            self::LandingPage => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Contact => 'heroicon-o-check-badge',
            self::CallMe => 'heroicon-o-check-badge',
            self::SaleOrder => 'heroicon-o-check-badge',
            self::Referral => 'heroicon-o-check-badge',
            self::Coupon => 'heroicon-o-check-badge',
            self::LandingPage => 'heroicon-o-check-badge',
        };
    }
}
