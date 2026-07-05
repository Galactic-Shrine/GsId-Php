# Migrazione di GsId 1.x → 2.0

`GsId 2.0.0` è una major release. Ripulisce i namespace di integrazione e rimuove le vecchie classi di compatibilità.

## 1. Tipo Doctrine spostato

La classe seguente è stata rimossa definitivamente:

```php
GalacticShrine\GsId\GsIdDoctrineType
```

Usa ora:

```php
GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType
```

### Prima

```php
use GalacticShrine\GsId\GsIdDoctrineType;

#[ORM\Column(type: GsIdDoctrineType::Name)]
private ?GsId $id = null;
```

### Dopo

```php
use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;

#[ORM\Column(type: GsidType::Name)]
private ?GsId $id = null;
```

Puoi anche usare direttamente il nome del tipo:

```php
#[ORM\Column(type: 'gsid')]
private ?GsId $id = null;
```

Il bundle Symfony registra automaticamente il tipo DBAL `gsid` quando `DoctrineBundle` è installato.

## 2. Configuratore Symfony spostato

La classe seguente è stata rinominata e spostata:

```php
GalacticShrine\GsId\GsIdSymfonyOptionsConfigurator
```

Nuovo nome:

```php
GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator
```

Se usi il bundle Symfony, normalmente non devi modificare nulla: il bundle registra questo servizio automaticamente.

Se usi la modalità senza bundle, aggiorna `config/services.gsid.yaml`:

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

## 3. Bridge Symfony rinominato

La classe seguente è stata rinominata e spostata:

```php
GalacticShrine\GsId\GsIdSymfonyUidBridge
```

Nuovo nome:

```php
GalacticShrine\GsId\Symfony\Bridge\GsIdToUid
```

### Prima

```php
use GalacticShrine\GsId\GsIdSymfonyUidBridge;
```

### Dopo

```php
use GalacticShrine\GsId\Symfony\Bridge\GsIdToUid;
```

Il nome `GsIdToUid` è intenzionale. `GsIdToUuid` non è stato scelto perché `GsId` è un identificatore a 256 bit, mentre un UUID standard è un identificatore a 128 bit.

## 4. Configurazione Symfony consigliata

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

La recipe `2.0` si trova in:

```txt
flex-recipes/galactic-shrine/gsid/2.0/
```

È pensata per essere pubblicata in un repository Symfony Flex pubblico o privato. Una volta pubblicata, può abilitare il bundle e copiare `config/packages/gsid.yaml` nel progetto Symfony che usa il pacchetto.

## 6. Comandi di verifica

```bash
composer dump-autoload
composer lint
composer test
```
