<?php

namespace App\Filament\Dash\Resources\FaqGroups\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class FaqGroupsTable
{
    public static function configure(Table $table): Table
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
}
