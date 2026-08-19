<?php

namespace App\Filament\Dash\Resources\SuccessCases\Pages;

use App\Filament\Dash\Resources\SuccessCases\SuccessCaseResource;
use App\Forms\MakeSlugStringTrait;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditSuccessCase extends EditRecord
{
    use MakeSlugStringTrait;

    protected static string $resource = SuccessCaseResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['avatar'] != $this->record->avatar && ! blank($this->record->avatar)) {
            Storage::disk('public')->delete($this->record->avatar);
        }

        if ($data['cover'] != $this->record->cover && ! blank($this->record->cover)) {
            Storage::disk('public')->delete($this->record->cover);
        }

        if (! blank($data['title']) || ! blank($data['slug'])) {
            $data['slug'] = self::makeSlugString(
                fullTitle: $data['title'],
                modelFullyClassName: self::getModel(),
                slug: $data['slug'],
                limitChars: 123,
                currentRecordId: $this->record->id
            );
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // $this->clearSiteCache();
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->after(function ($record) {
                    if (! blank($this->record->logotype)) {
                        Storage::disk('public')->delete($this->record->logotype);
                    }

                    if (! blank($this->record->avatar)) {
                        Storage::disk('public')->delete($this->record->avatar);
                    }

                    if (! blank($this->record->cover)) {
                        Storage::disk('public')->delete($this->record->cover);
                    }
                }),
        ];
    }

    // protected function getSavedNotification(): ?Notification
    // {
    //     return Notification::make()
    //         ->success()
    //         ->title('Item atualizado')
    //         ->body('O item foi atualizado com sucesso.');
    // }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
