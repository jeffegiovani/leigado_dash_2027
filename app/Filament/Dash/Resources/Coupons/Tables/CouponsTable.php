<?php

namespace App\Filament\Dash\Resources\Coupons\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('author.name')
                    ->sortable()
                    ->searchable()
                    ->label('Responsável'),

                Tables\Columns\TextColumn::make('visibility')
                    ->sortable()
                    ->searchable()
                    ->label('Visibilidade'),

                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->label('Avatar'),

                Tables\Columns\ImageColumn::make('cover')
                    ->label('Capa'),

                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->label('Título'),

                Tables\Columns\TextColumn::make('offer_headline')
                    ->searchable()
                    ->label('Headline'),

                // Tables\Columns\TextColumn::make('cta')
                //     ->searchable()
                //     ->label(''),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Criação'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Edição'),
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
}
