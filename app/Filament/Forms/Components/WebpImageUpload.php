<?php

namespace App\Filament\Forms\Components;

use Closure;
use Filament\Forms\Components\FileUpload;
use Illuminate\Image\Image as ImageContract;
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

    protected bool|Closure $shouldResizeOnServer = false;

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

        /** Todo o restante da aplicação lê e apaga estas imagens no disco `public`. */
        $this->disk('public');

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

        $image = Image::fromUpload($file);

        $image = $this->applyServerResize($image);

        $path = $image
            ->optimize($this->getOptimizeFormat(), $this->getOptimizeQuality())
            ->storeAs(
                path: $this->getDirectory() ?? '',
                name: $this->getUploadedFileNameForStorage($file),
                disk: $this->getDiskName(),
                options: ['visibility' => $this->getVisibility()],
            );

        return $path === false ? null : $path;
    }

    /**
     * Repete no servidor o redimensionamento que o FilePond faz no navegador.
     *
     * Sem isso o resize depende inteiramente do JavaScript do formulário: um
     * arquivo que chegue por outro caminho é gravado no tamanho original.
     * Usa as dimensões já declaradas no campo com `automaticallyResizeImagesTo*()`.
     */
    public function resizeOnServer(bool|Closure $condition = true): static
    {
        $this->shouldResizeOnServer = $condition;

        return $this;
    }

    public function shouldResizeOnServer(): bool
    {
        return (bool) $this->evaluate($this->shouldResizeOnServer);
    }

    /**
     * Aplica o alvo de redimensionamento declarado no campo, quando houver.
     */
    protected function applyServerResize(ImageContract $image): ImageContract
    {
        if (! $this->shouldResizeOnServer()) {
            return $image;
        }

        $width = (int) $this->getAutomaticallyResizeImagesWidth();
        $height = (int) $this->getAutomaticallyResizeImagesHeight();

        if ($width < 1 || $height < 1) {
            return $image;
        }

        return match ($this->getAutomaticallyResizeImagesMode()) {
            'contain' => $image->contain($width, $height),
            'force' => $image->resize($width, $height),
            default => $image->cover($width, $height),
        };
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
