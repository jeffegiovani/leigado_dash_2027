<?php

namespace App\Filament\Dash\Resources\SiteContacts;

use App\Filament\Dash\Resources\SiteContacts\Schemas\SiteContactForm;
use App\Filament\Dash\Resources\SiteContacts\Tables\SiteContactsTable;
use App\Models\SiteContact;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SiteContactResource extends Resource
{
    protected static ?string $model = SiteContact::class;

    protected static ?string $slug = 'site-form-contacts';

    // protected static string | \UnitEnum | null $navigationGroup = 'Configurações';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationLabel = 'Contatos via Site'; // Para Menus e para o Spotlight

    protected static ?int $navigationSort = 0;

    protected static ?string $modelLabel = 'Contato via Site';

    protected static ?string $pluralModelLabel = 'Contatos via Site';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 8;

    public static function form(Schema $schema): Schema
    {
        return SiteContactForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteContactsTable::configure($table);
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
            'index' => Pages\ListSiteContacts::route('/'),
            'create' => Pages\CreateSiteContact::route('/create'),
            'view' => Pages\ViewSiteContact::route('/{record}'),
            // 'edit' => Pages\EditSiteContact::route('/{record}/edit'),
        ];
    }
}
