# GalacticShrine GsId per PHP

**Lingue:** [Français](./README.md) · [English](./README.en.md) · [Español](./README.es.md) · **Italiano** · [日本語](./README.jp.md)

`GsId` è una libreria PHP per generare, analizzare, validare, serializzare e archiviare identificatori Galactic-Shrine a **256 bit**.

Include un core PHP puro, un tipo Doctrine DBAL, un bundle Symfony opzionale, un bridge Symfony e un template di recipe Symfony Flex.

## Panoramica

| Elemento | Valore |
| --- | --- |
| Pacchetto | `galactic-shrine/gsid` |
| Versione maggiore | `2.0.1` |
| PHP | `>= 8.1` |
| Licenza | `MPL-2.0` |
| Dimensione identificatore | 256 bit / 32 byte / 64 caratteri esadecimali |
| Tipo Doctrine | `gsid` |
| Bundle Symfony | `GalacticShrine\GsId\Symfony\GsIdBundle` |

## Installazione

```bash
composer require galactic-shrine/gsid:^2.0
```

## Formati supportati

`GsId` supporta due formati testuali.

### Formato `N`

Formato compatto, senza separatori:

```txt
0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef
```

Lunghezza: `64` caratteri.

### Formato `D`

Formato leggibile con separatori:

```txt
0123456789abcdef-01234567-89abcdef-01234567-89abcdef-0123456789abcdef
```

Schema gruppi: `16-8-8-8-8-16`.

Lunghezza: `69` caratteri.

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
    // Identificatore valido.
}
```

## Opzioni globali

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

`lock()` impedisce modifiche alla configurazione dopo il bootstrap dell’applicazione.

## Symfony con bundle

Abilita il bundle se Symfony Flex non lo fa automaticamente:

```php
// config/bundles.php
return [
    GalacticShrine\GsId\Symfony\GsIdBundle::class => ['all' => true],
];
```

Configurazione consigliata:

```yaml
# config/packages/gsid.yaml
gsid:
    default_case: Lower
    default_text_format: N
    default_json_format: D
    default_database_format: N
    lock: true
```

Il bundle registra automaticamente il tipo Doctrine DBAL `gsid` quando `DoctrineBundle` è disponibile.

## Symfony senza bundle

Il file seguente è fornito come configurazione alternativa:

```txt
config/services.gsid.yaml
```

Permette di registrare manualmente:

```php
GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator
```

Questo modo è utile se vuoi l’integrazione Symfony senza abilitare `GsIdBundle`.

## Doctrine DBAL

Da `2.0.0`, il tipo Doctrine si trova nel namespace bridge:

```php
use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;
```

Esempio di entità:

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

Puoi anche usare direttamente il nome del tipo:

```php
#[ORM\Column(type: 'gsid')]
private ?GsId $id = null;
```

## Bridge Symfony

`GsIdToUid` fornisce conversioni utili per route, DTO, form e serializer Symfony.

```php
use GalacticShrine\GsId\Symfony\Bridge\GsIdToUid;

$value = GsIdToUid::normalizeForRoute($id);
$id = GsIdToUid::denormalizeFromRoute($value);
```

Il nome `GsIdToUid` è intenzionale. `GsIdToUuid` sarebbe fuorviante, perché un `GsId` è un identificatore a 256 bit mentre un UUID standard è a 128 bit.

## Symfony Flex recipe

Un template di recipe è disponibile qui:

```txt
flex-recipes/galactic-shrine/gsid/2.0/
```

Può:

- abilitare `GsIdBundle`;
- copiare `config/packages/gsid.yaml` nel progetto Symfony che usa il pacchetto.

Importante: la cartella `flex-recipes/` inclusa è un template. Perché Symfony Flex la applichi automaticamente durante `composer require`, la recipe deve essere pubblicata in un repository Symfony Flex pubblico o privato.

## Migrazione da 1.x

`GsIdDoctrineType` è stato rimosso in `2.0.0`.

Prima:

```php
GalacticShrine\GsId\GsIdDoctrineType
```

Dopo:

```php
GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType
```

Vedi [UPGRADE-2.0.it.md](./UPGRADE-2.0.it.md).

## Sviluppo

```bash
composer install
composer lint
composer test
```

## Licenza

- Licenza ufficiale: [`LICENSE`](LICENSE)
- Nota IT: [`LICENSE.it.md`](LICENSE.it.md)
- Nota EN: [`LICENSE.en.md`](LICENSE.en.md)
- Nota FR: [`LICENSE.fr.md`](LICENSE.fr.md)
- Nota ES: [`LICENSE.es.md`](LICENSE.es.md)
- Nota JP: [`LICENSE.jp.md`](LICENSE.jp.md)

GsId is distributed under `MPL-2.0`.
