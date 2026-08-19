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
