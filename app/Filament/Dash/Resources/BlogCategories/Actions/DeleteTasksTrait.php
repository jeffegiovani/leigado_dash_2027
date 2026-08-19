<?php

namespace App\Filament\Dash\Resources\BlogCategories\Actions;

use App\Models\BlogCategory;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

trait DeleteTasksTrait
{
    public static function executeDeleteTasksSingle(
        BlogCategory $record,
        DeleteAction|ForceDeleteAction $action
    ) {
        $countRelated = $record->articles()->count();
        if ($countRelated > 0) {
            self::sendDeleteErrorNotification($countRelated);
            $action->cancel();
        }
    }

    public static function executeDeleteTasksBulk(
        Collection $records,
        DeleteBulkAction|ForceDeleteBulkAction $action
    ) {
        $recordWithRelatedItems = $records->firstWhere('articles_count', '>', 0);

        if ($recordWithRelatedItems) {
            self::sendDeleteErrorNotification($recordWithRelatedItems->articles_count);
            $action->cancel();
        }
    }

    protected static function sendDeleteErrorNotification(int $count = 0)
    {
        $title = $count > 1
            ? "Há {$count} Artigos Relacionados"
            : 'Há Um Artigo Relacionado';

        $body = $count > 1
            ? "Não foi possível excluir a categoria, há {$count} artigos que dependem dela."
            : 'Não foi possível excluir a categoria, há um artigo que depende dela.';

        Notification::make()
            ->title($title)
            ->body($body)
            ->danger()
            ->color('danger')
            ->persistent()
            ->actions([
                Action::make('understand')
                    ->label('Entendi')
                    ->color('gray')
                    ->button()
                    ->close(),
            ])
            ->send();
    }
}
