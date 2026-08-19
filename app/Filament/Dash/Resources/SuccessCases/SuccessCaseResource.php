<?php

namespace App\Filament\Dash\Resources\SuccessCases;

use App\Filament\Dash\Resources\SuccessCases\Schemas\SuccessCaseForm;
use App\Filament\Dash\Resources\SuccessCases\Tables\SuccessCasesTable;
use App\Models\SuccessCase;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SuccessCaseResource extends Resource
{
    protected static ?string $model = SuccessCase::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationLabel = 'Cases/Logos'; // Para Menus e para o Spotlight

    // protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Case/Logo';

    protected static ?string $pluralModelLabel = 'Cases/Logos';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'customer_name', 'testimony', 'content'];
    }

    public static function form(Schema $schema): Schema
    {
        return SuccessCaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuccessCasesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuccessCases::route('/'),
            'create' => Pages\CreateSuccessCase::route('/create'),
            'view' => Pages\ViewSuccessCase::route('/{record}'),
            'edit' => Pages\EditSuccessCase::route('/{record}/edit'),
        ];
    }
}
