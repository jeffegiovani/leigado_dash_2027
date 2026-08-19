<?php

namespace App\Filament\Dash\Resources\SuccessCases\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class SuccessCasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('updated_at', 'DESC')
            ->columns([
                Tables\Columns\TextColumn::make('visibility')
                    // ->searchable()
                    ->label('Visibilidade'),

                Tables\Columns\ImageColumn::make('logotype')
                    // ->circular()
                    ->label('Logotipo'),

                Tables\Columns\TextColumn::make('customer_name')
                    ->sortable()
                    ->searchable()
                    ->limit(20)
                    ->label('Cliente'),

                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->label('Avatar'),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->limit(20)
                    ->label('Depoente'),

                Tables\Columns\TextColumn::make('customer_location')
                    ->sortable()
                    ->searchable()
                    ->limit(20)
                    ->label('Região/Localização'),

                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Título do Case'),

                Tables\Columns\TextColumn::make('slug')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Slug de Acesso'),

                // Tables\Columns\TextColumn::make('cta')
                //     ->searchable()
                //     ->limit(20)
                //     ->label('Chamada'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Criação'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Atualização'),
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
