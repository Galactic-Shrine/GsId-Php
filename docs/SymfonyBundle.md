# GsId Symfony Bundle

## Installation

```bash
composer require galactic-shrine/gsid:^1.0
```

Enable the bundle if Symfony Flex did not do it automatically:

```php
// config/bundles.php
return [
    // ...
    GalacticShrine\GsId\Symfony\GsIdBundle::class => ['all' => true],
];
```

## Configuration

```yaml
# config/packages/gsid.yaml
gsid:
    default_case: Lower
    default_text_format: N
    default_json_format: D
    default_database_format: N
    lock: true
```

## Doctrine

When DoctrineBundle is installed, `GsIdBundle` automatically prepends this configuration:

```yaml
doctrine:
    dbal:
        types:
            gsid: GalacticShrine\GsId\GsIdDoctrineType
```

You do not need to add the type manually in `doctrine.yaml` unless you want to override it.

Example entity field:

```php
use Doctrine\ORM\Mapping as ORM;
use GalacticShrine\GsId\GsId;
use GalacticShrine\GsId\GsIdDoctrineType;

#[ORM\Column(type: GsIdDoctrineType::Name, unique: true)]
private ?GsId $id = null;
```

## Symfony Flex recipe

The package contains a recipe template under:

```txt
flex-recipes/galactic-shrine/gsid/1.0/
```

For automatic installation in external projects, publish this recipe to a Symfony Flex recipe repository or configure it in a private Flex endpoint.
