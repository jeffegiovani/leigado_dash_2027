<?php

use App\Enums\SiteConfigKeyEnum;
use App\Filament\Dash\Pages\SiteConfigs;
use App\Models\SiteConfig;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create();

    Gate::before(fn (): bool => true);

    $this->actingAs($this->admin);
});

it('carrega as opções gravadas no formulário', function () {
    SiteConfig::store(SiteConfigKeyEnum::PrivacyPolicy, '<p>Privacidade</p>');
    SiteConfig::store(SiteConfigKeyEnum::TermsOfUse, '<p>Termos</p>');
    SiteConfig::store(SiteConfigKeyEnum::WhatsappAttendants, [
        [
            'name' => 'Volmir Pagno',
            'phone' => '5546999119511',
            'phone_formatted' => '+55 46 9 9911 9511',
            'location' => null,
            'whatsapp_message' => 'Olá!',
            'avatar' => 'volmir.webp',
            'is_dairy_attendant' => true,
            'is_active' => true,
        ],
    ]);

    Livewire::test(SiteConfigs::class)
        ->assertSchemaStateSet(fn (array $state): array => [
            SiteConfigKeyEnum::PrivacyPolicy->value => '<p>Privacidade</p>',
            SiteConfigKeyEnum::TermsOfUse->value => '<p>Termos</p>',
        ] + $state)
        ->assertSuccessful();
});

it('grava atendentes e conteúdos legais', function () {
    Livewire::test(SiteConfigs::class)
        ->fillForm([
            SiteConfigKeyEnum::WhatsappAttendants->value => [
                [
                    'name' => 'Thalita Guedes',
                    'phone' => '5546999828048',
                    'phone_formatted' => '+55 46 9 9982 8048',
                    'location' => null,
                    'whatsapp_message' => 'Olá, Thalita!',
                    'avatar' => ['0d1c' => 'site-configs/attendants/thalita.webp'],
                    'is_dairy_attendant' => false,
                    'is_active' => true,
                ],
            ],
            SiteConfigKeyEnum::PrivacyPolicy->value => '<p>Nova política</p>',
            SiteConfigKeyEnum::TermsOfUse->value => '<p>Novos termos</p>',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(SiteConfig::valueFor(SiteConfigKeyEnum::PrivacyPolicy))->toBe('<p>Nova política</p>')
        ->and(SiteConfig::valueFor(SiteConfigKeyEnum::TermsOfUse))->toBe('<p>Novos termos</p>');

    $attendants = SiteConfig::valueFor(SiteConfigKeyEnum::WhatsappAttendants);

    expect($attendants)->toHaveCount(1)
        ->and($attendants[0]['name'])->toBe('Thalita Guedes')
        ->and($attendants[0]['phone'])->toBe('5546999828048')
        ->and($attendants[0]['avatar'])->toBe('site-configs/attendants/thalita.webp');
});

it('exige nome, telefone e mensagem do atendente', function () {
    Livewire::test(SiteConfigs::class)
        ->fillForm([
            SiteConfigKeyEnum::WhatsappAttendants->value => [
                [
                    'name' => null,
                    'phone' => null,
                    'whatsapp_message' => null,
                    'avatar' => null,
                ],
            ],
        ])
        ->call('save')
        ->assertHasFormErrors();
});
