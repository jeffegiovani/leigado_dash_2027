<?php

namespace App\Filament\Dash\Resources\Users\Schemas;

use App\Filament\Forms\Components\WebpImageUpload;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Rawilk\FilamentPasswordInput\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
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
}
