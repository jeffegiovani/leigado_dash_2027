<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Mantém o disco em sincronia com os caminhos de imagem gravados no model.
 *
 * Os arquivos são apagados quando o registro é excluído e quando o caminho é
 * trocado ou limpo, independentemente de onde a operação partiu (página de
 * edição, ação em massa, comando de console).
 */
trait DeletesManagedImages
{
    /**
     * Atributos que guardam o caminho de uma imagem no disco.
     *
     * @return array<int, string>
     */
    abstract public function managedImageAttributes(): array;

    /**
     * Disco em que os uploads deste model são gravados.
     */
    public function managedImageDisk(): string
    {
        return 'public';
    }

    protected static function bootDeletesManagedImages(): void
    {
        static::updated(function (self $model): void {
            foreach ($model->managedImageAttributes() as $attribute) {
                $original = $model->getOriginal($attribute);

                if (blank($original) || $original === $model->getAttribute($attribute)) {
                    continue;
                }

                $model->deleteManagedImage($original);
            }
        });

        static::deleted(function (self $model): void {
            if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                return;
            }

            foreach ($model->managedImageAttributes() as $attribute) {
                $model->deleteManagedImage($model->getAttribute($attribute));
            }
        });
    }

    protected function deleteManagedImage(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        Storage::disk($this->managedImageDisk())->delete($path);
    }
}
