<?php

namespace App\Filament\Dash\Resources\SiteContacts\Tables;

use App\Enums\ContactChannelEnum;
use App\Models\SiteContact;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class SiteContactsTable
{
    public static function configure(Table $table): Table
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
}
