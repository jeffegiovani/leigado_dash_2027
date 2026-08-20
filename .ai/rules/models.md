---
paths:
  - app/Models/SiteConfig.php
---

# Models

## site_configs é o contrato compartilhado com o portal
A tabela `site_configs` (chave em `App\Enums\SiteConfigKeyEnum`, valor JSON) é lida diretamente pelo portal (leigado_portal_2027), que tem um `App\Models\SiteConfig` espelhado e cacheia por 10 minutos.

Ao mudar o formato de uma chave (ex.: campos de `whatsapp_attendants`), atualize o model e as views do portal na mesma leva. O portal precisa apontar `DB_DATABASE` para o banco deste dash.

## Atendentes do WhatsApp usam `segments`, não flag booleana
Cada atendente em `whatsapp_attendants` tem `segments: ["general"|"dairy"]` (App\Enums\AttendantSegmentEnum) — um mesmo atendente pode aparecer nos dois contextos com um único cadastro. O booleano `is_dairy_attendant` foi removido pela migração `normalize_whatsapp_attendant_segments`.

`avatar` é sempre caminho relativo no disco `public` (`site-configs/attendants/*.webp`). O portal não tem mais fallback: nem `config('leigado.attendants')`, nem avatar servido pelos estáticos. Se o storage do dash não estiver acessível pela URL de `STORAGE_FILES_URL`, as fotos quebram no site.

`whatsapp_message` é texto literal (não é mais chave de tradução) — as views do portal não aplicam `__()` nele.
