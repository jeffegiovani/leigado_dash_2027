<?php

use App\Models\User;
use Illuminate\Support\Facades\Gate;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->admin = User::factory()->create();

    // O smoke test verifica roteamento e renderização, não autorização.
    // As permissões reais do Shield são exercitadas em produção pelo super_admin.
    Gate::before(fn (): bool => true);
});

it('exibe o dashboard na raiz', function () {
    actingAs($this->admin)->get('/')->assertSuccessful();
});

it('exibe a listagem de cada resource', function (string $path) {
    actingAs($this->admin)->get($path)->assertSuccessful();
})->with([
    'blog-articles',
    'blog-categories',
    'coupons',
    'faqs',
    'faq-group',
    'jobs',
    'site-form-contacts',
    'success-cases',
    'usuarios',
]);

it('exibe as páginas customizadas', function (string $path) {
    actingAs($this->admin)->get($path)->assertSuccessful();
})->with([
    'site-configs',
    'profile',
]);

it('redireciona visitante anônimo para o login', function () {
    get('/')->assertRedirect();
});
