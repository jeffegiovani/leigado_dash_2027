<?php

use App\Filament\Forms\Components\WebpImageUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

function temporaryUpload(UploadedFile $file): TemporaryUploadedFile
{
    Storage::fake('tmp-for-tests');

    $name = TemporaryUploadedFile::generateHashNameWithOriginalNameEmbedded($file);

    Storage::disk('tmp-for-tests')->putFileAs('livewire-tmp', $file, $name);

    return TemporaryUploadedFile::createFromLivewire('/'.$name);
}

it('converte um JPEG enviado para WebP', function () {
    Storage::fake('public');

    $component = WebpImageUpload::make('cover')
        ->disk('public')
        ->directory('blog/covers');

    $file = temporaryUpload(UploadedFile::fake()->image('foto.jpg', 400, 300));

    expect($component->shouldOptimize($file))->toBeTrue();

    $path = $component->storeOptimizedFile($file);

    expect($path)->toEndWith('.webp')
        ->and($path)->toStartWith('blog/covers/');

    Storage::disk('public')->assertExists($path);

    // O arquivo precisa ser WebP de verdade, não só ter a extensão.
    $bytes = Storage::disk('public')->get($path);

    expect(substr($bytes, 0, 4))->toBe('RIFF')
        ->and(substr($bytes, 8, 4))->toBe('WEBP');

    [$width, $height] = getimagesizefromstring($bytes);

    expect($width)->toBe(400)->and($height)->toBe(300);
});

it('não reconverte SVG', function () {
    $component = WebpImageUpload::make('logo')->disk('public');

    $file = temporaryUpload(UploadedFile::fake()->create('logo.svg', 4, 'image/svg+xml'));

    expect($component->shouldOptimize($file))->toBeFalse();
});

it('redimensiona no servidor quando o campo pede', function () {
    Storage::fake('public');

    $component = WebpImageUpload::make('avatar')
        ->disk('public')
        ->directory('site-configs/attendants')
        ->automaticallyResizeImagesMode('cover')
        ->automaticallyResizeImagesToWidth('96')
        ->automaticallyResizeImagesToHeight('96')
        ->resizeOnServer();

    $path = $component->storeOptimizedFile(temporaryUpload(UploadedFile::fake()->image('gigante.jpg', 2400, 1600)));

    [$width, $height] = getimagesizefromstring(Storage::disk('public')->get($path));

    expect($width)->toBe(96)->and($height)->toBe(96);
});

it('mantém as dimensões originais quando o resize no servidor não é pedido', function () {
    Storage::fake('public');

    $component = WebpImageUpload::make('cover')
        ->disk('public')
        ->directory('blog/covers')
        ->automaticallyResizeImagesMode('cover')
        ->automaticallyResizeImagesToWidth('405')
        ->automaticallyResizeImagesToHeight('135');

    $path = $component->storeOptimizedFile(temporaryUpload(UploadedFile::fake()->image('capa.jpg', 800, 600)));

    [$width, $height] = getimagesizefromstring(Storage::disk('public')->get($path));

    expect($width)->toBe(800)->and($height)->toBe(600);
});
