# GalacticShrine GsId for PHP

**Languages:** [Français](./README.md) · **English** · [Español](./README.es.md) · [Italiano](./README.it.md) · [日本語](./README.jp.md)

`GsId` is a PHP library for generating, parsing, validating, serializing, and storing Galactic-Shrine **256-bit** identifiers.

It provides a pure PHP core, a Doctrine DBAL type, an optional Symfony bundle, a Symfony bridge, and a Symfony Flex recipe template.

## Overview

| Item | Value |
| --- | --- |
| Package | `galactic-shrine/gsid` |
| Major version | `2.0.0` |
| PHP | `>= 8.3` |
| License | `MPL-2.0` |
| Identifier size | 256 bits / 32 bytes / 64 hexadecimal characters |
| Doctrine type | `gsid` |
| Symfony bundle | `GalacticShrine\GsId\Symfony\GsIdBundle` |

## Installation

```bash
composer require galactic-shrine/gsid:^2.0
```

## Supported formats

`GsId` supports two text formats.

### `N` format

Compact format without separators:

```txt
0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef
```

Length: `64` characters.

### `D` format

Readable format with separators:

```txt
0123456789abcdef-01234567-89abcdef-01234567-89abcdef-0123456789abcdef
```

Group pattern: `16-8-8-8-8-16`.

Length: `69` characters.

## Pure PHP usage

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
    // Valid identifier.
}
```

## Global options

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

`lock()` prevents configuration changes after application bootstrap.

## Symfony with bundle

Enable the bundle if Symfony Flex does not do it automatically:

```php
// config/bundles.php
return [
    GalacticShrine\GsId\Symfony\GsIdBundle::class => ['all' => true],
];
```

Recommended configuration:

```yaml
# config/packages/gsid.yaml
gsid:
    default_case: Lower
    default_text_format: N
    default_json_format: D
    default_database_format: N
    lock: true
```

The bundle automatically registers the Doctrine DBAL type `gsid` when `DoctrineBundle` is available.

## Symfony without bundle

The following file is provided as an alternative configuration:

```txt
config/services.gsid.yaml
```

It manually registers:

```php
GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator
```

Use this mode when you want Symfony integration without enabling `GsIdBundle`.

## Doctrine DBAL

Since `2.0.0`, the Doctrine type lives in the bridge namespace:

```php
use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;
```

Entity example:

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

You may also use the type name directly:

```php
#[ORM\Column(type: 'gsid')]
private ?GsId $id = null;
```

## Symfony bridge

`GsIdToUid` provides convenient conversions for Symfony routes, DTOs, forms, and serializers.

```php
use GalacticShrine\GsId\Symfony\Bridge\GsIdToUid;

$value = GsIdToUid::normalizeForRoute($id);
$id = GsIdToUid::denormalizeFromRoute($value);
```

The name `GsIdToUid` is intentional. `GsIdToUuid` would be misleading because a `GsId` is a 256-bit identifier while a standard UUID is a 128-bit identifier.

## Symfony Flex recipe

A recipe template is provided here:

```txt
flex-recipes/galactic-shrine/gsid/2.0/
```

It can:

- enable `GsIdBundle`;
- copy `config/packages/gsid.yaml` into the consumer Symfony project.

Important: the included `flex-recipes/` directory is a template. For Symfony Flex to apply it automatically during `composer require`, the recipe must be published in a public or private Symfony Flex recipe repository.

## Migration from 1.x

`GsIdDoctrineType` was removed in `2.0.0`.

Before:

```php
GalacticShrine\GsId\GsIdDoctrineType
```

After:

```php
GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType
```

See [UPGRADE-2.0.en.md](./UPGRADE-2.0.en.md).

## Development

```bash
composer install
composer lint
composer test
```

## License

- Official license: [`LICENSE`](LICENSE)
- English notice: [`LICENSE.en.md`](LICENSE.en.md)
- French notice: [`LICENSE.fr.md`](LICENSE.fr.md)
- Spanish notice: [`LICENSE.es.md`](LICENSE.es.md)
- Italian notice: [`LICENSE.it.md`](LICENSE.it.md)
- Japanese notice: [`LICENSE.jp.md`](LICENSE.jp.md)

GsId is distributed under `MPL-2.0`.
