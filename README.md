# GalacticShrine GsId pour PHP

**Langues :** **Français** · [English](./README.en.md) · [Español](./README.es.md) · [Italiano](./README.it.md) · [日本語](./README.jp.md)

`GsId` est une bibliothèque PHP pour créer, parser, valider, sérialiser et stocker des identifiants Galactic-Shrine de **256 bits**.

Elle fournit un cœur PHP pur, un type Doctrine DBAL, un bundle Symfony optionnel, un bridge Symfony et une recipe Symfony Flex prête à publier.

## Vue d’ensemble

| Élément | Valeur |
| --- | --- |
| Package | `galactic-shrine/gsid` |
| Version majeure | `2.0.1` |
| PHP | `>= 8.1` |
| Licence | `MPL-2.0` |
| Type d’identifiant | 256 bits / 32 octets / 64 caractères hexadécimaux |
| Format Doctrine | `gsid` |
| Bundle Symfony | `GalacticShrine\GsId\Symfony\GsIdBundle` |

## Installation

```bash
composer require galactic-shrine/gsid:^2.0
```

## Formats supportés

`GsId` supporte deux formats texte.

### Format `N`

Format compact, sans séparateur :

```txt
0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef
```

Longueur : `64` caractères.

### Format `D`

Format lisible avec séparateurs :

```txt
0123456789abcdef-01234567-89abcdef-01234567-89abcdef-0123456789abcdef
```

Groupe : `16-8-8-8-8-16`.

Longueur : `69` caractères.

## Utilisation PHP pure

```php
use GalacticShrine\GsId\GsId;
use GalacticShrine\GsId\GsIdCase;
use GalacticShrine\GsId\GsIdFormat;
use GalacticShrine\GsId\GsIdValidator;

$id = GsId::newGsId();

echo $id->toString(GsIdFormat::N, GsIdCase::Lower);
echo $id->toString(GsIdFormat::D, GsIdCase::Upper);

$parsed = GsId::fromString((string) $id);

if (GsIdValidator::isValid((string) $id)) {
    // Identifiant valide.
}
```

## Options globales

```php
use GalacticShrine\GsId\GsIdCase;
use GalacticShrine\GsId\GsIdFormat;
use GalacticShrine\GsId\GsIdOptions;

GsIdOptions::configure(
    defaultCase: GsIdCase::Lower,
    defaultTextFormat: GsIdFormat::N,
    defaultJsonFormat: GsIdFormat::D,
    defaultDatabaseFormat: GsIdFormat::N,
);

GsIdOptions::lock();
```

`lock()` empêche les modifications de configuration après l’initialisation de l’application.

## Symfony avec bundle

Active le bundle si Symfony Flex ne le fait pas automatiquement :

```php
// config/bundles.php
return [
    GalacticShrine\GsId\Symfony\GsIdBundle::class => ['all' => true],
];
```

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

Le bundle enregistre automatiquement le type Doctrine DBAL `gsid` si `DoctrineBundle` est présent.

## Symfony sans bundle

Le fichier suivant est fourni comme configuration alternative :

```txt
config/services.gsid.yaml
```

Il permet d’enregistrer manuellement :

```php
GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator
```

Ce mode est utile si tu veux utiliser Symfony sans activer `GsIdBundle`.

## Doctrine DBAL

Depuis `2.0.0`, le type Doctrine est dans le namespace bridge :

```php
use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;
```

Exemple d’entité :

```php
use Doctrine\ORM\Mapping as ORM;
use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;
use GalacticShrine\GsId\GsId;

#[ORM\Entity]
final class ExampleEntity
{
    #[ORM\Id]
    #[ORM\Column(type: GsidType::Name, unique: true)]
    private ?GsId $id = null;
}
```

Tu peux aussi utiliser directement le nom du type :

```php
#[ORM\Column(type: 'gsid')]
private ?GsId $id = null;
```

## Bridge Symfony

`GsIdToUid` fournit des conversions utiles pour les routes, DTO, formulaires ou serializers Symfony.

```php
use GalacticShrine\GsId\Symfony\Bridge\GsIdToUid;

$value = GsIdToUid::normalizeForRoute($id);
$id = GsIdToUid::denormalizeFromRoute($value);
```

Le nom `GsIdToUid` est volontaire. `GsIdToUuid` serait trompeur, car un `GsId` est un identifiant 256 bits alors qu’un UUID standard est un identifiant 128 bits.

## Symfony Flex recipe

Une recipe modèle est fournie ici :

```txt
flex-recipes/galactic-shrine/gsid/2.0/
```

Elle peut :

- activer `GsIdBundle` ;
- copier `config/packages/gsid.yaml` dans le projet Symfony utilisateur.

Important : le dossier `flex-recipes/` inclus dans ce dépôt est un modèle. Pour que Symfony Flex l’applique automatiquement lors d’un `composer require`, la recipe doit être publiée dans un dépôt Symfony Flex public ou privé.

## Migration depuis 1.x

`GsIdDoctrineType` a été supprimé en `2.0.0`.

Avant :

```php
GalacticShrine\GsId\GsIdDoctrineType
```

Après :

```php
GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType
```

Voir [UPGRADE-2.0.md](./UPGRADE-2.0.md).

Guides de migration disponibles : [FR](./UPGRADE-2.0.md), [EN](./UPGRADE-2.0.en.md), [ES](./UPGRADE-2.0.es.md), [IT](./UPGRADE-2.0.it.md), [JP](./UPGRADE-2.0.jp.md).

## Développement

```bash
composer install
composer lint
composer test
```

## Licence

- Licence officielle : [`LICENSE`](LICENSE)
- Notice FR : [`LICENSE.fr.md`](LICENSE.fr.md)
- Notice EN : [`LICENSE.en.md`](LICENSE.en.md)
- Notice ES : [`LICENSE.es.md`](LICENSE.es.md)
- Notice IT : [`LICENSE.it.md`](LICENSE.it.md)
- Notice JP : [`LICENSE.jp.md`](LICENSE.jp.md)

GsId is distributed under `MPL-2.0`.
