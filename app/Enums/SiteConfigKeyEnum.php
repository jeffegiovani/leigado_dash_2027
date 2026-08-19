<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SiteConfigKeyEnum: string implements HasLabel
{
    case WhatsappAttendants = 'whatsapp_attendants';
    case PrivacyPolicy = 'privacy_policy';
    case TermsOfUse = 'terms_of_use';

    public function getLabel(): string
    {
        return match ($this) {
            self::WhatsappAttendants => 'Atendentes do WhatsApp',
            self::PrivacyPolicy => 'Política de Privacidade',
            self::TermsOfUse => 'Termos de Uso',
        };
    }

    /**
     * Descrição gravada na coluna `info` para orientar quem consulta o banco.
     */
    public function getInfo(): string
    {
        return match ($this) {
            self::WhatsappAttendants => 'Atendentes exibidos na WhatsApp Bubble do site',
            self::PrivacyPolicy => 'Conteúdo da página de política de privacidade do site',
            self::TermsOfUse => 'Conteúdo da página de termos de uso do site',
        };
    }
}
