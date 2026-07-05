# Migración de GsId 1.x → 2.0

`GsId 2.0.0` es una versión mayor. Limpia los namespaces de integración y elimina las clases históricas de compatibilidad.

## 1. Tipo Doctrine movido

La siguiente clase fue eliminada definitivamente:

```php
GalacticShrine\GsId\GsIdDoctrineType
```

Usa ahora:

```php
GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType
```

### Antes

```php
use GalacticShrine\GsId\GsIdDoctrineType;

#[ORM\Column(type: GsIdDoctrineType::Name)]
private ?GsId $id = null;
```

### Después

```php
use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;

#[ORM\Column(type: GsidType::Name)]
private ?GsId $id = null;
```

También puedes usar directamente el nombre del tipo:

```php
#[ORM\Column(type: 'gsid')]
private ?GsId $id = null;
```

El bundle Symfony registra automáticamente el tipo DBAL `gsid` cuando `DoctrineBundle` está instalado.

## 2. Configurador Symfony movido

La siguiente clase fue renombrada y movida:

```php
GalacticShrine\GsId\GsIdSymfonyOptionsConfigurator
```

Nuevo nombre:

```php
GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator
```

Si usas el bundle Symfony, normalmente no tienes que cambiar nada: el bundle registra este servicio automáticamente.

Si usas el modo sin bundle, actualiza `config/services.gsid.yaml`:

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

## 3. Bridge Symfony renombrado

La siguiente clase fue renombrada y movida:

```php
GalacticShrine\GsId\GsIdSymfonyUidBridge
```

Nuevo nombre:

```php
GalacticShrine\GsId\Symfony\Bridge\GsIdToUid
```

### Antes

```php
use GalacticShrine\GsId\GsIdSymfonyUidBridge;
```

### Después

```php
use GalacticShrine\GsId\Symfony\Bridge\GsIdToUid;
```

El nombre `GsIdToUid` es intencional. No se eligió `GsIdToUuid` porque `GsId` es un identificador de 256 bits, mientras que un UUID estándar es un identificador de 128 bits.

## 4. Configuración Symfony recomendada

```yaml
# config/packages/gsid.yaml
gsid:
    default_case: Lower
    default_text_format: N
    default_json_format: D
    default_database_format: N
    lock: true
```

## 5. Recipe Symfony Flex

La recipe `2.0` se encuentra en:

```txt
flex-recipes/galactic-shrine/gsid/2.0/
```

Está pensada para publicarse en un repositorio Symfony Flex público o privado. Una vez publicada, puede activar el bundle y copiar `config/packages/gsid.yaml` en el proyecto Symfony consumidor.

## 6. Comandos de verificación

```bash
composer dump-autoload
composer lint
composer test
```
