# GalacticShrine GsId (PHP)

**Languages:** [Français](./README.md) · **English** · [Español](./README.es.md) · [Italiano](./README.it.md) · [日本語](./README.jp.md)

PHP library to generate, parse, validate, and convert 256-bit `GsId` identifiers.

## Status

- Version: `1.0.0`
- Level: production

## Key points

- `N` and `D` formats
- `Upper` and `Lower` casing support
- `GsIdOptions::configure(...)`
- `GsIdOptions::lock()`
- Symfony bridge included
- optional Symfony bundle included
- Doctrine DBAL type included

## Symfony configuration with bundle

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

## Symfony configuration without bundle

See `config/services.gsid.yaml`.
