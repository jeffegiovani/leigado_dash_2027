<?php

namespace Database\Seeders;

use App\Enums\AttendantSegmentEnum;
use App\Enums\SiteConfigKeyEnum;
use App\Models\SiteConfig;
use Illuminate\Database\Seeder;

class SiteConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteConfig::store(SiteConfigKeyEnum::WhatsappAttendants, $this->attendants());

        SiteConfig::store(SiteConfigKeyEnum::PrivacyPolicy, $this->stub('privacy-policy'));

        SiteConfig::store(SiteConfigKeyEnum::TermsOfUse, $this->stub('terms-of-use'));
    }

    /**
     * Atendentes portados do `config/leigado.php` do portal.
     *
     * @return array<int, array{name: string, phone: string, phone_formatted: string, location: ?string, whatsapp_message: string, avatar: string, segments: array<int, string>, is_active: bool}>
     */
    protected function attendants(): array
    {
        return [
            [
                'name' => 'Volmir Pagno',
                'phone' => '5546999119511',
                'phone_formatted' => '+55 46 9 9911 9511',
                'location' => null,
                'whatsapp_message' => 'Olá, Volmir! Tudo bem? Gostaria de saber mais sobre as soluções da Leigado',
                'avatar' => 'site-configs/attendants/volmir.webp',
                'segments' => [AttendantSegmentEnum::General->value, AttendantSegmentEnum::Dairy->value],
                'is_active' => true,
            ],
            [
                'name' => 'Thalita Guedes',
                'phone' => '5546999828048',
                'phone_formatted' => '+55 46 9 9982 8048',
                'location' => null,
                'whatsapp_message' => 'Olá, Thalita! Tudo bem? Gostaria de saber mais sobre as soluções da Leigado',
                'avatar' => 'site-configs/attendants/thalita.webp',
                'segments' => [AttendantSegmentEnum::General->value],
                'is_active' => true,
            ],
        ];
    }

    /**
     * Conteúdo HTML inicial das páginas legais, portado das views do portal.
     */
    protected function stub(string $name): string
    {
        return trim(file_get_contents(__DIR__.'/stubs/'.$name.'.html'));
    }
}
