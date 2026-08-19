<?php

namespace App\Filament\Dash\Resources\Users\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
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
}
