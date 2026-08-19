<?php

namespace App\Filament\Dash\Resources;

use App\Enums\ContactChannelEnum;
use App\Filament\Dash\Resources\SiteContactResource\Pages;
use App\Models\SiteContact;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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
        return $schema
            ->components([
                Schemas\Components\Section::make('Detalhes')
                    ->description('Detalhes do contato recebido nos canais do site')
                    ->aside()
                    ->columns([
                        'md' => 2,
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->maxLength(80)
                            ->default(null)
                            ->label('Nome'),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(80)
                            ->label('Email'),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20)
                            ->default(null)
                            ->label('Fone'),

                        Forms\Components\Select::make('channel')
                            ->required()
                            ->options(ContactChannelEnum::class)
                            ->searchable()
                            ->preload()
                            ->label('Canal de Contato'),

                        Forms\Components\Textarea::make('content')
                            ->disabled()
                            ->rows(5)
                            ->columnSpanFull()
                            ->label('Detalhes do Payload'),

                        // Forms\Components\Textarea::make('content')
                        //     ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('updated_at', 'DESC')
            ->columns([
                Tables\Columns\TextColumn::make('channel')
                    ->searchable()
                    ->sortable()
                    ->label('Canal do Site')
                    ->badge(),

                Tables\Columns\TextColumn::make('name')
                    ->limit(20)
                    ->copyable()
                    ->icon('heroicon-o-clipboard-document-check')
                    ->iconPosition('after')
                    ->searchable()
                    ->label('Nome'),

                Tables\Columns\TextColumn::make('email')
                    ->limit(20)
                    ->copyable()
                    ->icon('heroicon-o-clipboard-document-check')
                    ->iconPosition('after')
                    ->searchable()
                    ->label('E-mail'),

                Tables\Columns\TextColumn::make('phone')
                    ->copyable()
                    ->icon('heroicon-o-clipboard-document-check')
                    ->iconPosition('after')
                    ->searchable()
                    ->label('Fone'),

                Tables\Columns\TextColumn::make('content')
                    ->limit(40)
                    ->searchable()
                    ->label('Payload'),

                // Tables\Columns\TextColumn::make('content')
                //     ->label('Detalhes')
                //     ->toggleable()
                //     ->formatStateUsing(function (SiteContact $record) {
                //         return match ($record->channel) {
                //             ContactChannelEnum::Contact =>
                //             new HtmlString('<span class="leading-none">' . str($record->content?->message)->limit(20) ?? '' . '</span>'),

                //             ContactChannelEnum::Advertise =>
                //             new HtmlString('<span class="leading-none">' . str($record->content?->description)->limit(20) ?? '' . '</span>'),

                //             ContactChannelEnum::Proposal => new HtmlString('<span class="leading-none text-xs font-semibold text-slate-500 px-2 bg-slate-300/20 rounded border border-slate-300/40">REF:</span><span class="block leading-none">' . $record->content?->property_ref ?? '' . '</span>'),
                //             ContactChannelEnum::Evaluate => new HtmlString('<span class="leading-none">' . str($record->content?->description)->limit(20) ?? '' . '</span>'),
                //             ContactChannelEnum::CallMe => new HtmlString('<span class="leading-none text-xs font-semibold text-slate-500 px-2 bg-slate-300/20 rounded border border-slate-300/40">Sugestão de Horário:</span><span class="block leading-none">' . str($record->content?->hour)->limit(20) ?? '' . '</span>'),
                //             ContactChannelEnum::Interested => new HtmlString('<span class="leading-none">' . str($record->content?->description)->limit(20) ?? '' . '</span>'),

                //             default => new HtmlString('<span class="block leading-none">N/A</span>'),
                //         };
                //     }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->tooltip(fn ($state): string => "às {$state->format('H:i:s')}")
                    ->label('Recebido em'),
                // ->formatStateUsing(fn ($state) => $state->lang('pt-BR')->format('d, M/Y H:i')),
            ])
            ->filters([
                // Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                // \Filament\Actions\EditAction::make(),
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
            'index' => Pages\ListSiteContacts::route('/'),
            'create' => Pages\CreateSiteContact::route('/create'),
            'view' => Pages\ViewSiteContact::route('/{record}'),
            // 'edit' => Pages\EditSiteContact::route('/{record}/edit'),
        ];
    }
}
