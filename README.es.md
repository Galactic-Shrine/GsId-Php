# GalacticShrine GsId para PHP

**Idiomas:** [Français](./README.md) · [English](./README.en.md) · **Español** · [Italiano](./README.it.md) · [日本語](./README.jp.md)

`GsId` es una biblioteca PHP para generar, analizar, validar, serializar y almacenar identificadores Galactic-Shrine de **256 bits**.

Incluye un núcleo PHP puro, un tipo Doctrine DBAL, un bundle Symfony opcional, un bridge Symfony y una plantilla de recipe Symfony Flex.

## Resumen

| Elemento | Valor |
| --- | --- |
| Paquete | `galactic-shrine/gsid` |
| Versión mayor | `2.0.0` |
| PHP | `>= 8.3` |
| Licencia | `MPL-2.0` |
| Tamaño del identificador | 256 bits / 32 bytes / 64 caracteres hexadecimales |
| Tipo Doctrine | `gsid` |
| Bundle Symfony | `GalacticShrine\GsId\Symfony\GsIdBundle` |

## Instalación

```bash
composer require galactic-shrine/gsid:^2.0
```

## Formatos soportados

`GsId` soporta dos formatos de texto.

### Formato `N`

Formato compacto, sin separadores:

```txt
0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef
```

Longitud: `64` caracteres.

### Formato `D`

Formato legible con separadores:

```txt
0123456789abcdef-01234567-89abcdef-01234567-89abcdef-0123456789abcdef
```

Patrón de grupos: `16-8-8-8-8-16`.

Longitud: `69` caracteres.

## Uso PHP puro

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
    // Identificador válido.
}
```

## Opciones globales

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

`lock()` bloquea los cambios de configuración después del arranque de la aplicación.

## Symfony con bundle

Activa el bundle si Symfony Flex no lo hace automáticamente:

```php
// config/bundles.php
return [
    GalacticShrine\GsId\Symfony\GsIdBundle::class => ['all' => true],
];
```

Configuración recomendada:

```yaml
# config/packages/gsid.yaml
gsid:
    default_case: Lower
    default_text_format: N
    default_json_format: D
    default_database_format: N
    lock: true
```

El bundle registra automáticamente el tipo Doctrine DBAL `gsid` cuando `DoctrineBundle` está disponible.

## Symfony sin bundle

El siguiente archivo se proporciona como configuración alternativa:

```txt
config/services.gsid.yaml
```

Permite registrar manualmente:

```php
GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator
```

Este modo es útil si quieres integración Symfony sin activar `GsIdBundle`.

## Doctrine DBAL

Desde `2.0.0`, el tipo Doctrine está en el namespace bridge:

```php
use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;
```

Ejemplo de entidad:

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

También puedes usar directamente el nombre del tipo:

```php
#[ORM\Column(type: 'gsid')]
private ?GsId $id = null;
```

## Bridge Symfony

`GsIdToUid` proporciona conversiones útiles para rutas, DTO, formularios y serializers Symfony.

```php
use GalacticShrine\GsId\Symfony\Bridge\GsIdToUid;

$value = GsIdToUid::normalizeForRoute($id);
$id = GsIdToUid::denormalizeFromRoute($value);
```

El nombre `GsIdToUid` es intencional. `GsIdToUuid` sería engañoso, porque un `GsId` es un identificador de 256 bits mientras que un UUID estándar es de 128 bits.

## Symfony Flex recipe

Hay una plantilla de recipe disponible aquí:

```txt
flex-recipes/galactic-shrine/gsid/2.0/
```

Puede:

- activar `GsIdBundle`;
- copiar `config/packages/gsid.yaml` en el proyecto Symfony consumidor.

Importante: el directorio `flex-recipes/` incluido es una plantilla. Para que Symfony Flex lo aplique automáticamente durante `composer require`, la recipe debe publicarse en un repositorio Symfony Flex público o privado.

## Migración desde 1.x

`GsIdDoctrineType` fue eliminado en `2.0.0`.

Antes:

```php
GalacticShrine\GsId\GsIdDoctrineType
```

Después:

```php
GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType
```

Ver [UPGRADE-2.0.es.md](./UPGRADE-2.0.es.md).

## Desarrollo

```bash
composer install
composer lint
composer test
```

## Licencia

- Licencia oficial: [`LICENSE`](LICENSE)
- Nota ES: [`LICENSE.es.md`](LICENSE.es.md)
- Nota EN: [`LICENSE.en.md`](LICENSE.en.md)
- Nota FR: [`LICENSE.fr.md`](LICENSE.fr.md)
- Nota IT: [`LICENSE.it.md`](LICENSE.it.md)
- Nota JP: [`LICENSE.jp.md`](LICENSE.jp.md)

GsId is distributed under `MPL-2.0`.
