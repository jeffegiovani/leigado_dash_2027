<?php

namespace App\Filament\Dash\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class SiteConfigs extends Page
{
    use HasPageShield;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-americas';

    protected static ?string $title = 'Opções do Site';

    protected static string|\UnitEnum|null $navigationGroup = 'Configurações';

    protected string $view = 'filament.dash.pages.site-configs';
}
