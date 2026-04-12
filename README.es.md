# GalacticShrine GsId (PHP)

**Idiomas:** [Français](./README.md) · [English](./README.en.md) · **Español** · [Italiano](./README.it.md) · [日本語](./README.jp.md)

Biblioteca PHP para generar, analizar, validar y convertir identificadores `GsId` de 256 bits.

## Estado

- Versión: `1.0.2`
- Nivel: producción

## Puntos clave

- formatos `N` y `D`
- soporte `Upper` y `Lower`
- `GsIdOptions::configure(...)`
- `GsIdOptions::lock()`
- bridge Symfony incluido
- bundle Symfony opcional incluido
- tipo Doctrine DBAL incluido

## Configuración Symfony con bundle

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

## Configuración Symfony sin bundle

Ver `config/services.gsid.yaml`.
