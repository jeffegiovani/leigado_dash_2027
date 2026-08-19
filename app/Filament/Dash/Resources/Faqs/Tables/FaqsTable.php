<?php

namespace App\Filament\Dash\Resources\Faqs\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
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
}
