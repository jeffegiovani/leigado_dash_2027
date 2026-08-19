<?php

use App\Filament\Dash\Resources\BlogResource\Pages\CreateBlog;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Storage::fake('public');
    Gate::before(fn (): bool => true);

    $this->admin = User::factory()->create();
    actingAs($this->admin);
});

it('grava o artigo convertendo as imagens enviadas para WebP', function () {
    $category = BlogCategory::create(['title' => 'Pecuária', 'slug' => 'pecuaria']);

    Livewire::test(CreateBlog::class)
        ->fillForm([
            'title' => 'Artigo de teste da migração',
            'slug' => 'artigo-de-teste-da-migracao',
            'cta' => 'Resumo do artigo de teste.',
            'content' => '<p>Conteúdo do artigo.</p>',
            'author_id' => $this->admin->id,
            'categories' => [$category->id],
            'visibility' => 'public',
            'thumb' => UploadedFile::fake()->image('thumb.jpg', 900, 300),
            'cover' => UploadedFile::fake()->image('cover.png', 1500, 500),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $blog = Blog::query()->where('slug', 'artigo-de-teste-da-migracao')->sole();

    // Os caminhos gravados apontam para arquivos .webp nos diretórios corretos.
    expect($blog->thumb)->toStartWith('blog/thumbs/')->toEndWith('.webp')
        ->and($blog->cover)->toStartWith('blog/covers/')->toEndWith('.webp');

    // E o conteúdo no disco é WebP de verdade — inclusive o PNG enviado.
    foreach ([$blog->thumb, $blog->cover] as $path) {
        Storage::disk('public')->assertExists($path);

        $bytes = Storage::disk('public')->get($path);

        expect(substr($bytes, 0, 4))->toBe('RIFF')
            ->and(substr($bytes, 8, 4))->toBe('WEBP');
    }

    // A relação author (agora BelongsTo) resolve sem tocar na tabela users.
    expect($blog->author->is($this->admin))->toBeTrue();
});
