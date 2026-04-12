# GalacticShrine GsId (PHP)

**Lingue:** [Français](./README.md) · [English](./README.en.md) · [Español](./README.es.md) · **Italiano** · [日本語](./README.jp.md)

Libreria PHP per generare, analizzare, validare e convertire identificatori `GsId` a 256 bit.

## Stato

- Versione: `1.0.0`
- Livello: produzione

## Punti chiave

- formati `N` e `D`
- supporto `Upper` e `Lower`
- `GsIdOptions::configure(...)`
- `GsIdOptions::lock()`
- bridge Symfony incluso
- bundle Symfony opzionale incluso
- tipo Doctrine DBAL incluso

## Configurazione Symfony con bundle

`config/packages/gsid.yaml`:

```yaml
gsid:
  default_case: Lower
  default_text_format: N
  default_json_format: D
  default_database_format: N
  lock: true
```

`config/bundles.php`:

```php
return [
    GalacticShrine\GsId\Symfony\GsIdBundle::class => ['all' => true],
];
```

## Configurazione Symfony senza bundle

Vedi `config/services.gsid.yaml`.
