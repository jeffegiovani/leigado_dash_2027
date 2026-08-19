<?php

namespace App\Filament\Dash\Resources;

use App\Filament\Dash\Resources\FaqResource\Pages;
use App\Models\Faq;
use App\Models\FaqGroup;
use Filament\Actions\Action;
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
        return $schema
            ->components([
                Schemas\Components\Section::make('Perguntas Frequentes - FAQs')
                    ->description('Crie respostas que serão exibidas dentro do site nos respectivos produtos no qual foram agrupadas')
                    ->aside()
                    ->schema([
                        Forms\Components\Select::make('group_id')
                            ->options(
                                fn () => FaqGroup::query()->orderBy('title')->pluck('title', 'id')
                            )
                            ->required()
                            ->searchable()
                            ->columnSpanFull()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('group_title')
                                    ->required()
                                    ->maxLength(120)
                                    ->minLength(2)
                                    ->label('Título do Grupo'),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $data = [
                                    'title' => $data['group_title'],
                                    'slug' => str($data['group_title'])->replace(['/', '\\'], [' ', ' '])->slug('-', 'pt_BR', ['@' => '-']),
                                ];

                                return FaqGroup::query()->create($data)->getKey();
                            })
                            ->createOptionAction(
                                function (Action $action) {
                                    $action->modalWidth('xl')
                                        ->modalSubmitAction(function (Action $action) {
                                            $action->keyBindings(['enter'])->label('Criar e Usar Grupo');
                                        });
                                }
                            )
                            ->label('Grupo de Respostas'),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(160)
                            ->columnSpanFull()
                            ->label('Pergunta Frequente'),

                        Forms\Components\TextInput::make('slug')
                            // ->visibleOn('edit')
                            // ->required()
                            ->maxLength(120)
                            ->columnSpanFull()
                            ->hint(new HtmlString('<small>Deixe vazio pra gerar automaticamente</small>'))
                            ->label('URL de Acesso'),

                        Forms\Components\MarkdownEditor::make('content')
                            ->columnSpanFull()
                            ->disableToolbarButtons([
                                'attachFiles',
                                'codeBlock',
                                'heading',
                                // 'link',
                                'table',
                            ])
                            ->required()
                            ->label('Resposta da Pergunta Frequente'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('updated_at', 'DESC')
            ->columns([
                Tables\Columns\TextColumn::make('group.title')
                    ->searchable()
                    ->sortable()
                    ->label('Grupo/Produto'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(33)
                    ->label('Pergunta'),

                Tables\Columns\TextColumn::make('content')
                    // ->searchable()
                    ->sortable(false)
                    ->limit(44)
                    ->toggleable()
                    ->label('Resposta'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->label('Criação'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
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
