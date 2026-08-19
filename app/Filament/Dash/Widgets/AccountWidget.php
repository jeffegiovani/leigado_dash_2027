<?php

namespace App\Filament\Dash\Widgets;

use Filament\Widgets\Widget;

class AccountWidget extends Widget
{
    // protected static ?int $sort = -3;

    protected static bool $isLazy = true;

    public function getColumns(): int
    {
        return 1;
    }

    protected int|string|array $columnSpan = [
        'default' => 'full',
        // 'md' => 1,
    ];

    /**
     * @var view-string
     */
    protected string $view = 'filament-panels::widgets.account-widget';
}
