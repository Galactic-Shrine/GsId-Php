# GalacticShrine GsId (PHP)

**Langues :** **Français** · [English](./README.en.md) · [Español](./README.es.md) · [Italiano](./README.it.md) · [日本語](./README.jp.md)

Bibliothèque PHP pour générer, parser, valider et convertir des identifiants `GsId` 256 bits.

## Statut

- Version : `1.0.0`
- Niveau : production

## Points clés

- formats `N` et `D`
- support de casse `Upper` et `Lower`
- `GsIdOptions::configure(...)`
- `GsIdOptions::lock()`
- bridge Symfony fourni
- bundle Symfony optionnel fourni
- type Doctrine DBAL fourni

## Configuration Symfony avec bundle

`config/packages/gsid.yaml` :

```yaml
gsid:
  default_case: Lower
  default_text_format: N
  default_json_format: D
  default_database_format: N
  lock: true
```

`config/bundles.php` :

```php
return [
    GalacticShrine\GsId\Symfony\GsIdBundle::class => ['all' => true],
];
```

## Configuration Symfony sans bundle

Voir `config/services.gsid.yaml`.
