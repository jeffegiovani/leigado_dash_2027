<?php

namespace App\Filament\Dash\Resources\Faqs;

use App\Filament\Dash\Resources\Faqs\Schemas\FaqForm;
use App\Filament\Dash\Resources\Faqs\Tables\FaqsTable;
use App\Models\Faq;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $slug = 'faqs';

    protected static string|\UnitEnum|null $navigationGroup = 'Perguntas Frequentes';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationLabel = 'Perguntas e Respostas'; // Para Menus e para o Spotlight

    protected static ?int $navigationSort = 0;

    protected static ?string $modelLabel = 'Resposta';

    protected static ?string $pluralModelLabel = 'Respostas';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'content'];
    }

    public static function form(Schema $schema): Schema
    {
        return FaqForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FaqsTable::configure($table);
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'view' => Pages\ViewFaq::route('/{record}'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([]);
    }
}
