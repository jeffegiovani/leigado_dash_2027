<?php

namespace App\Filament\Dash\Resources\FaqGroups\Actions;

use App\Models\FaqGroup;
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
        FaqGroup $record,
        DeleteAction|ForceDeleteAction $action
    ) {
        $countRelated = $record->faqs()->count();
        if ($countRelated > 0) {
            self::sendDeleteErrorNotification($countRelated);
            $action->cancel();
        }
    }

    public static function executeDeleteTasksBulk(
        Collection $records,
        DeleteBulkAction|ForceDeleteBulkAction $action
    ) {
        $recordWithRelatedItems = $records->firstWhere('faqs_count', '>', 0);

        if ($recordWithRelatedItems) {
            self::sendDeleteErrorNotification($recordWithRelatedItems->articles_count);
            $action->cancel();
        }
    }

    protected static function sendDeleteErrorNotification(int $count = 0)
    {
        $title = $count > 1
            ? "Há {$count} FAQs Relacionados"
            : 'Há Uma FAQ Relacionada';

        $body = $count > 1
            ? "Não foi possível excluir o grupo, há {$count} FAQs que dependem dele."
            : 'Não foi possível excluir o grupo, há uma FAQ que depende delae';

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
