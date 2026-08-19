<?php

namespace App\Filament\Dash\Resources;

use App\Filament\Dash\Resources\UserResource\Pages;
use App\Filament\Forms\Components\WebpImageUpload;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Rawilk\FilamentPasswordInput\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'usuarios';

    protected static string|\UnitEnum|null $navigationGroup = 'Configurações';

    protected static ?string $navigationLabel = 'Usuários'; // Para Menus e para o Spotlight

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user';

    // protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Usuário';

    protected static ?string $pluralModelLabel = 'Usuários';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    protected static int $globalSearchResultsLimit = 8;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
            ])
            ->schema([
                Schemas\Components\Section::make('Informações Gerais')
                    ->columns([
                        'md' => 4,
                    ])
                    ->schema([
                        WebpImageUpload::make('avatar_url')
                            ->avatar()
                            ->directory('avatars')
                            ->imageEditor()
                            ->imageEditorMode(2)
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth('120')
                            ->imageResizeTargetHeight('120')
                            ->optimize('webp')
                            ->label('Avatar'),

                        Schemas\Components\Group::make()
                            ->columnSpan([
                                'md' => 3,
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(191)
                                    ->label('Nome'),

                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(191)
                                    ->label('E-mail'),

                                // Forms\Components\DateTimePicker::make('email_verified_at'),

                                Password::make('password')
                                    ->label('Senha')
                                    ->copyable()
                                    ->regeneratePassword()
                                    ->inlineSuffix()
                                    ->maxLength(20)
                                    ->newPasswordLength(10)
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create'),
                            ]),

                    ]),

                Schemas\Components\Section::make('Acesso')
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->columnSpanFull()
                            ->relationship('roles', 'name')
                            ->searchable()
                            ->bulkToggleable()
                            ->columns([
                                'sm' => 2,
                            ])
                            ->label('Permissões de acesso'),
                    ]),

                // Forms\Components\Toggle::make('is_admin')
                //     ->required(),

                // Forms\Components\Toggle::make('is_active')
                //     ->required(),

                // Forms\Components\Textarea::make('custom_fields')
                //     ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->modifyQueryUsing(function (Builder $query): Builder {
                if (auth()->user()->id != 1) {
                    $query->where('id', '!=', 1);
                }

                return $query;
            })
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->circular()
                    ->label('Avatar'),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nome'),

                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->label('E-mail'),

                // Tables\Columns\TextColumn::make('email_verified_at')
                //     ->dateTime()
                //     ->sortable(),

                // Tables\Columns\IconColumn::make('is_admin')
                //     ->boolean(),

                // Tables\Columns\IconColumn::make('is_active')
                //     ->boolean(),

                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),

                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
