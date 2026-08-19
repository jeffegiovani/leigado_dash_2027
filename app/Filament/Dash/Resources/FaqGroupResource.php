<?php

namespace App\Filament\Dash\Resources;

use App\Filament\Dash\Resources\FaqGroupResource\Pages;
use App\Models\FaqGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class FaqGroupResource extends Resource
{
    protected static ?string $model = FaqGroup::class;

    protected static ?string $slug = 'faq-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Perguntas Frequentes';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationLabel = 'Grupos de Respostas'; // Para Menus e para o Spotlight

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Grupo de Respostas';

    protected static ?string $pluralModelLabel = 'Grupos de Respostas';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns([
                // 'md' => 2,
            ])
            ->schema([
                Schemas\Components\Section::make('Agrupamento de FAQs')
                    ->description('Crie grupos de FAQ que serão exibidas nos respectivos produtos dentro do site')
                    ->aside()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(80)
                            ->label('Título'),

                        Forms\Components\TextInput::make('slug')
                            ->maxLength(125)
                            ->default(null)
                            ->hint(new HtmlString('<small>Deixe vazio pra gerar automaticamente</small>'))
                            ->label('URL de Acesso'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label('COD'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Nome do Grupo'),

                Tables\Columns\TextColumn::make('slug')
                    // ->searchable()
                    ->label('URL de Acesso'),

                Tables\Columns\TextColumn::make('faqs_count')
                    ->sortable()
                    ->counts('faqs')
                    ->alignCenter()
                    ->label('F.A.Q.s'),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Criação'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Atualização'),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                // \Filament\Actions\BulkActionGroup::make([
                //     \Filament\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
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
            'index' => Pages\ListFaqGroups::route('/'),
            'create' => Pages\CreateFaqGroup::route('/create'),
            'view' => Pages\ViewFaqGroup::route('/{record}'),
            'edit' => Pages\EditFaqGroup::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([]);
    }
}
