<?php

namespace App\Filament\Dash\Resources;

use App\Enums\ResourceVisibilityEnum;
use App\Filament\Dash\Resources\JobResource\Pages;
use App\Models\Job;
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

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Vagas de Emprego'; // Para Menus e para o Spotlight

    // protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Vaga de Emprego';

    protected static ?string $pluralModelLabel = 'Vagas de Emprego';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'content'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Group::make()
                    ->columnSpan([
                        // 'md' => 2,
                        // '2xl' => 1,
                    ])
                    ->columns(1)
                    ->schema([
                        Forms\Components\ToggleButtons::make('visibility')
                            ->options(ResourceVisibilityEnum::class)
                            ->hiddenLabel()
                            ->required()
                            ->default(ResourceVisibilityEnum::Public)
                            // ->grouped()
                            ->inline()
                            // ->hint(fn() => view('forms.resource-visibility-hint-helper'))
                            ->label('Visibilidade'),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(120)
                            ->columnSpanFull()
                            ->label('Titulo da Vaga'),

                        Forms\Components\TextInput::make('slug')
                            // ->required()
                            // ->unique(ignoreRecord: true)
                            ->maxLength(125)
                            // ->visibleOn('edit')
                            ->columnSpanFull()
                            ->hint(new HtmlString('<small>Deixe vazio para gerar automaticamente</small>'))
                            ->label('Url de Acesso'),

                        Schemas\Components\Group::make()
                            ->columnSpan([
                                // 'md' => 2,
                                // '2xl' => 1,
                            ])
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('location')
                                    ->required()
                                    ->maxLength(40)
                                    ->placeholder('Dois Vizinhos, PR / Remoto / Externo')
                                    ->label('Localização'),

                                Forms\Components\Select::make('author_id')
                                    ->relationship(
                                        name: 'author',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: function (Builder $query): Builder {
                                            if (auth()->user()->id != 1) {
                                                $query->where('id', '!=', 1);
                                            }

                                            return $query;
                                        }
                                    )
                                    ->default(auth()->id())
                                    ->required()
                                    ->label('Responsável pela Vaga'),
                            ]),

                    ]),

                Schemas\Components\Group::make()
                    ->columnSpan([
                        // 'md' => 2,
                        // '2xl' => 1,
                    ])
                    ->columns(1)
                    ->schema([
                        Forms\Components\MarkdownEditor::make('content')
                            ->required()
                            ->disableToolbarButtons([
                                'attachFiles',
                                'codeBlock',
                                'heading',
                                // 'link',
                                'table',
                            ])
                            ->columnSpanFull()
                            ->label('Detalhamento da Vaga'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('updated_at', 'DESC')
            ->columns([
                Tables\Columns\TextColumn::make('author.name')
                    ->sortable()
                    ->label('Responsável'),

                Tables\Columns\TextColumn::make('visibility')
                    ->sortable()
                    ->searchable()
                    ->label('Visibilidade'),

                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->limit(50)
                    ->label('Título'),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Url de Acesso'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Criado em'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Atualizado em'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'view' => Pages\ViewJob::route('/{record}'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}
