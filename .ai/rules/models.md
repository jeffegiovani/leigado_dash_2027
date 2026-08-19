---
paths:
  - app/Models/SiteConfig.php
---

# Models

## site_configs é o contrato compartilhado com o portal
A tabela `site_configs` (chave em `App\Enums\SiteConfigKeyEnum`, valor JSON) é lida diretamente pelo portal (leigado_portal_2027), que tem um `App\Models\SiteConfig` espelhado e cacheia por 10 minutos.

Ao mudar o formato de uma chave (ex.: campos de `whatsapp_attendants`), atualize o model e as views do portal na mesma leva. O portal precisa apontar `DB_DATABASE` para o banco deste dash.
