<?php

namespace App\Filament\Dash\Pages;

use Filament\Forms;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Panel;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    protected static string $routePath = '/';

    protected static ?string $title = '';

    public static function getNavigationLabel(): string
    {
        return 'Painel Inicial';
    }

    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-rectangle-group';
    }

    public static function getRoutePath(Panel $panel): string
    {
        return static::$routePath;
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 2,
            'lg' => 4,
        ];
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Group::make()
                    ->schema([
                        Forms\Components\DatePicker::make('startDate')
                            ->label('Data Inicial'),

                        Forms\Components\DatePicker::make('endDate')
                            ->label('Data Final'),
                    ])
                    ->columns([
                        'default' => 2,
                    ])
                    ->columnSpan([
                        'default' => 'full',
                        // 'sm' => 1,
                    ]),
            ]);
    }
}
