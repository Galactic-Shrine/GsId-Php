# Migration GsId 1.x → 2.0

`GsId 2.0.0` est une version majeure. Elle nettoie les namespaces d’intégration et supprime les classes de compatibilité historiques.

## 1. Type Doctrine déplacé

La classe suivante a été supprimée définitivement :

```php
GalacticShrine\GsId\GsIdDoctrineType
```

Utilise maintenant :

```php
GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType
```

### Avant

```php
use GalacticShrine\GsId\GsIdDoctrineType;

#[ORM\Column(type: GsIdDoctrineType::Name)]
private ?GsId $id = null;
```

### Après

```php
use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;

#[ORM\Column(type: GsidType::Name)]
private ?GsId $id = null;
```

Tu peux aussi utiliser le nom du type directement :

```php
#[ORM\Column(type: 'gsid')]
private ?GsId $id = null;
```

Le bundle Symfony enregistre automatiquement le type DBAL `gsid` lorsque `DoctrineBundle` est installé.

## 2. Configurateur Symfony déplacé

La classe suivante a été renommée et déplacée :

```php
GalacticShrine\GsId\GsIdSymfonyOptionsConfigurator
```

Nouveau nom :

```php
GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator
```

Si tu utilises le bundle Symfony, tu n’as normalement rien à modifier : le bundle enregistre ce service automatiquement.

Si tu utilises le mode sans bundle, mets à jour `config/services.gsid.yaml` :

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

## 3. Bridge Symfony renommé

La classe suivante a été renommée et déplacée :

```php
GalacticShrine\GsId\GsIdSymfonyUidBridge
```

Nouveau nom :

```php
GalacticShrine\GsId\Symfony\Bridge\GsIdToUid
```

### Avant

```php
use GalacticShrine\GsId\GsIdSymfonyUidBridge;
```

### Après

```php
use GalacticShrine\GsId\Symfony\Bridge\GsIdToUid;
```

Le nom `GsIdToUid` est volontaire. `GsIdToUuid` n’a pas été retenu, car `GsId` est un identifiant 256 bits alors qu’un UUID standard est un identifiant 128 bits.

## 4. Configuration Symfony recommandée

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

La recipe `2.0` se trouve dans :

```txt
flex-recipes/galactic-shrine/gsid/2.0/
```

Elle est destinée à être publiée dans un dépôt Symfony Flex public ou privé. Une fois publiée, elle peut activer le bundle et copier `config/packages/gsid.yaml` dans le projet Symfony utilisateur.

## 6. Commandes de vérification

```bash
composer dump-autoload
composer lint
composer test
```
