<?php

use App\Filament\Dash\Resources\Blogs\Pages\EditBlog;
use App\Models\Blog;
use App\Models\Coupon;
use App\Models\SuccessCase;
use App\Models\User;
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

it('apaga thumb e capa ao excluir o artigo', function () {
    Storage::disk('public')->put('blog/thumbs/thumb.webp', 'thumb');
    Storage::disk('public')->put('blog/covers/cover.webp', 'cover');

    $blog = Blog::factory()->create([
        'thumb' => 'blog/thumbs/thumb.webp',
        'cover' => 'blog/covers/cover.webp',
    ]);

    Livewire::test(EditBlog::class, ['record' => $blog->getRouteKey()])
        ->callAction('delete');

    expect(Blog::query()->find($blog->id))->toBeNull();

    Storage::disk('public')->assertMissing('blog/thumbs/thumb.webp');
    Storage::disk('public')->assertMissing('blog/covers/cover.webp');
});

it('apaga a imagem anterior quando ela é substituída', function () {
    Storage::disk('public')->put('blog/thumbs/antiga.webp', 'antiga');
    Storage::disk('public')->put('blog/thumbs/nova.webp', 'nova');

    $blog = Blog::factory()->create(['thumb' => 'blog/thumbs/antiga.webp']);

    $blog->update(['thumb' => 'blog/thumbs/nova.webp']);

    Storage::disk('public')->assertMissing('blog/thumbs/antiga.webp');
    Storage::disk('public')->assertExists('blog/thumbs/nova.webp');
});

it('apaga a imagem anterior quando o campo é limpo', function () {
    Storage::disk('public')->put('blog/covers/antiga.webp', 'antiga');

    $blog = Blog::factory()->create(['cover' => 'blog/covers/antiga.webp']);

    $blog->update(['cover' => null]);

    Storage::disk('public')->assertMissing('blog/covers/antiga.webp');
});

it('apaga o avatar ao excluir o usuário', function () {
    Storage::disk('public')->put('avatars/avatar.webp', 'avatar');

    $user = User::factory()->create(['avatar_url' => 'avatars/avatar.webp']);

    $user->delete();

    Storage::disk('public')->assertMissing('avatars/avatar.webp');
});

it('apaga as imagens ao excluir cupom e caso de sucesso', function () {
    Storage::disk('public')->put('coupons/avatar.webp', 'a');
    Storage::disk('public')->put('coupons/cover.webp', 'b');
    Storage::disk('public')->put('cases/logotype.webp', 'c');

    $coupon = Coupon::factory()->create([
        'avatar' => 'coupons/avatar.webp',
        'cover' => 'coupons/cover.webp',
    ]);
    $case = SuccessCase::factory()->create([
        'logotype' => 'cases/logotype.webp',
    ]);

    $coupon->delete();
    $case->delete();

    Storage::disk('public')->assertMissing('coupons/avatar.webp');
    Storage::disk('public')->assertMissing('coupons/cover.webp');
    Storage::disk('public')->assertMissing('cases/logotype.webp');
});
