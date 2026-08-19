<?php

namespace App\Filament\Dash\Resources\BlogCategories\Tables;

use App\Models\BlogCategory;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class BlogCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            // ->recordClasses(fn(BlogCategory $record) => match ($record->trashed()) {
            //     false => '',
            //     true => '!bg-danger-50 dark:!bg-danger-950',
            //     default => '',
            // })
            ->defaultSort('title', 'ASC')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->label('Nome da Categoria'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('URL de Acesso'),

                Tables\Columns\TextColumn::make('articles_count')
                    ->sortable()
                    ->label('Artigos')
                    ->counts('articles')
                    ->alignCenter(),

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
