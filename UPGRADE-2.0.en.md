# Migrating GsId 1.x → 2.0

`GsId 2.0.0` is a major release. It cleans up integration namespaces and removes historical compatibility classes.

## 1. Doctrine type moved

The following class was permanently removed:

```php
GalacticShrine\GsId\GsIdDoctrineType
```

Use this class instead:

```php
GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType
```

### Before

```php
use GalacticShrine\GsId\GsIdDoctrineType;

#[ORM\Column(type: GsIdDoctrineType::Name)]
private ?GsId $id = null;
```

### After

```php
use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;

#[ORM\Column(type: GsidType::Name)]
private ?GsId $id = null;
```

You can also use the type name directly:

```php
#[ORM\Column(type: 'gsid')]
private ?GsId $id = null;
```

The Symfony bundle automatically registers the `gsid` DBAL type when `DoctrineBundle` is installed.

## 2. Symfony configurator moved

The following class was renamed and moved:

```php
GalacticShrine\GsId\GsIdSymfonyOptionsConfigurator
```

New name:

```php
GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator
```

If you use the Symfony bundle, you normally do not need to change anything: the bundle registers this service automatically.

If you use the no-bundle mode, update `config/services.gsid.yaml`:

```yaml
services:
  GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator:
    arguments:
      $options:
        default_case: Lower
        default_text_format: N
        default_json_format: D
        default_database_format: N
        lock: true
    tags:
      - { name: kernel.event_listener, event: kernel.request, method: onKernelRequest, priority: 4096 }
```

## 3. Symfony bridge renamed

The following class was renamed and moved:

```php
GalacticShrine\GsId\GsIdSymfonyUidBridge
```

New name:

```php
GalacticShrine\GsId\Symfony\Bridge\GsIdToUid
```

### Before

```php
use GalacticShrine\GsId\GsIdSymfonyUidBridge;
```

### After

```php
use GalacticShrine\GsId\Symfony\Bridge\GsIdToUid;
```

The name `GsIdToUid` is intentional. `GsIdToUuid` was not used because `GsId` is a 256-bit identifier, while a standard UUID is a 128-bit identifier.

## 4. Recommended Symfony configuration

```yaml
# config/packages/gsid.yaml
gsid:
    default_case: Lower
    default_text_format: N
    default_json_format: D
    default_database_format: N
    lock: true
```

## 5. Symfony Flex recipe

The `2.0` recipe is located in:

```txt
flex-recipes/galactic-shrine/gsid/2.0/
```

It is meant to be published in a public or private Symfony Flex recipe repository. Once published, it can enable the bundle and copy `config/packages/gsid.yaml` into the consumer Symfony project.

## 6. Verification commands

```bash
composer dump-autoload
composer lint
composer test
```
