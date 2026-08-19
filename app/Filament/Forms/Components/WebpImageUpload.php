<?php

namespace App\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Image;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Upload de imagem que converte o arquivo para WebP no servidor.
 *
 * Substitui o `->optimize('webp')` que vinha do pacote joshembling/image-optimizer,
 * usando o componente de imagem nativo do Laravel (`Illuminate\Image`).
 */
class WebpImageUpload extends FileUpload
{
    protected string|Closure|null $optimizeFormat = 'webp';

    protected int|Closure $optimizeQuality = 75;

    /**
     * Formatos que não devem ser rasterizados/reconvertidos.
     *
     * @var array<int, string>
     */
    protected array $unoptimizableMimeTypes = ['image/svg+xml', 'image/gif'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->image();

        $this->getUploadedFileNameForStorageUsing(
            static function (WebpImageUpload $component, TemporaryUploadedFile $file): string {
                $extension = $component->shouldOptimize($file)
                    ? $component->getOptimizeFormat()
                    : $file->getClientOriginalExtension();

                if ($component->shouldPreserveFilenames()) {
                    return Str::of($file->getClientOriginalName())->beforeLast('.')->slug().'.'.$extension;
                }

                return Str::ulid().'.'.$extension;
            },
        );

        $this->saveUploadedFileUsing(
            static fn (WebpImageUpload $component, TemporaryUploadedFile $file): ?string => $component->storeOptimizedFile($file),
        );
    }

    /**
     * Converte o arquivo enviado e o grava no disco, devolvendo o caminho salvo.
     *
     * Arquivos que não devem ser reprocessados (SVG, GIF, não-imagens) caem no
     * comportamento padrão do Filament.
     */
    public function storeOptimizedFile(TemporaryUploadedFile $file): ?string
    {
        if (! $this->shouldOptimize($file)) {
            return $this->saveUploadedFile($file);
        }

        $path = Image::fromUpload($file)
            ->optimize($this->getOptimizeFormat(), $this->getOptimizeQuality())
            ->storeAs(
                path: $this->getDirectory() ?? '',
                name: $this->getUploadedFileNameForStorage($file),
                disk: $this->getDiskName(),
                options: ['visibility' => $this->getVisibility()],
            );

        return $path === false ? null : $path;
    }

    public function optimize(string|Closure|null $format = 'webp', int|Closure $quality = 75): static
    {
        $this->optimizeFormat = $format;
        $this->optimizeQuality = $quality;

        return $this;
    }

    public function getOptimizeFormat(): ?string
    {
        return $this->evaluate($this->optimizeFormat);
    }

    public function getOptimizeQuality(): int
    {
        return $this->evaluate($this->optimizeQuality);
    }

    public function shouldOptimize(TemporaryUploadedFile $file): bool
    {
        $mimeType = (string) $file->getMimeType();

        return filled($this->getOptimizeFormat())
            && str_starts_with($mimeType, 'image/')
            && ! in_array($mimeType, $this->unoptimizableMimeTypes, true);
    }
}
