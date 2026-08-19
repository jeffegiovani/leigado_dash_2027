<?php

namespace App\Filament\Dash\Resources\Blogs\Tables;

use App\Enums\ResourceVisibilityEnum;
use App\Models\Blog;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class BlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->defaultSort('created_at', 'DESC')
            ->recordClasses(
                fn (Blog $record): string => match (true) {
                    // $record->trashed() => '!bg-danger-100 dark:!bg-danger-950',
                    $record->visibility == ResourceVisibilityEnum::Private => '!bg-warning-50 dark:!bg-warning-950',
                    $record->visibility == ResourceVisibilityEnum::Unlisted => '!bg-success-50 dark:!bg-success-950',
                    default => '',
                }
            )
            ->columns([
                Tables\Columns\TextColumn::make('author.name')
                    ->sortable()
                    ->label('Autor')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('visibility')
                    ->badge()
                    ->label('Visibilidade'),

                Tables\Columns\ImageColumn::make('thumb')
                    ->sortable(false)
                    ->label('Capa'),

                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->label('Titulo/URL')
                    ->searchable()
                    ->limit(42)
                    ->description(
                        description: fn (Blog $record): string => str()->limit($record->slug, 40),
                        position: 'bellow'
                    ),

                Tables\Columns\TextColumn::make('cta')
                    ->label('CTA')
                    ->limit(42),

                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->label('Criação')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->label('Atualização')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
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
