# GalacticShrine GsId (PHP)

**Langues :** **Français** · [English](./README.en.md) · [Español](./README.es.md) · [Italiano](./README.it.md) · [日本語](./README.jp.md)

Bibliothèque PHP pour générer, parser, valider et convertir des identifiants `GsId` 256 bits.

## Statut

- Version : `1.0.2`
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


## Symfony Bundle / Composer

Depuis `v1.0.4`, le bundle Symfony peut enregistrer automatiquement le type Doctrine DBAL `gsid` si DoctrineBundle est présent.

Configuration recommandée :

```yaml
# config/packages/gsid.yaml
gsid:
    default_case: Lower
    default_text_format: N
    default_json_format: D
    default_database_format: N
    lock: true
```

Une recipe Symfony Flex modèle est disponible dans :

```txt
flex-recipes/galactic-shrine/gsid/1.0/
```

Elle peut activer le bundle et copier `config/packages/gsid.yaml` lors de l'installation Composer si elle est publiée dans un dépôt de recipes Flex.
