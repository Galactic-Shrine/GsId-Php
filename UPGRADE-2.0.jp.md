# GsId 1.x → 2.0 移行ガイド

`GsId 2.0.0` はメジャーリリースです。統合用 namespace を整理し、過去互換用の古いクラスを削除しました。

## 1. Doctrine 型の移動

次のクラスは完全に削除されました。

```php
GalacticShrine\GsId\GsIdDoctrineType
```

今後は次のクラスを使用します。

```php
GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType
```

### 旧

```php
use GalacticShrine\GsId\GsIdDoctrineType;

#[ORM\Column(type: GsIdDoctrineType::Name)]
private ?GsId $id = null;
```

### 新

```php
use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;

#[ORM\Column(type: GsidType::Name)]
private ?GsId $id = null;
```

型名を直接指定することもできます。

```php
#[ORM\Column(type: 'gsid')]
private ?GsId $id = null;
```

`DoctrineBundle` がインストールされている場合、Symfony bundle は DBAL 型 `gsid` を自動登録します。

## 2. Symfony configurator の移動

次のクラスはリネームされ、移動されました。

```php
GalacticShrine\GsId\GsIdSymfonyOptionsConfigurator
```

新しい名前:

```php
GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator
```

Symfony bundle を使っている場合、通常は変更不要です。bundle がこの service を自動登録します。

bundle なしで使っている場合は、`config/services.gsid.yaml` を更新してください。

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

## 3. Symfony bridge のリネーム

次のクラスはリネームされ、移動されました。

```php
GalacticShrine\GsId\GsIdSymfonyUidBridge
```

新しい名前:

```php
GalacticShrine\GsId\Symfony\Bridge\GsIdToUid
```

### 旧

```php
use GalacticShrine\GsId\GsIdSymfonyUidBridge;
```

### 新

```php
use GalacticShrine\GsId\Symfony\Bridge\GsIdToUid;
```

`GsIdToUid` という名前は意図的です。`GsId` は 256-bit identifier であり、標準 UUID は 128-bit identifier なので、`GsIdToUuid` という名前は採用していません。

## 4. 推奨 Symfony 設定

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

`2.0` recipe は次の場所にあります。

```txt
flex-recipes/galactic-shrine/gsid/2.0/
```

これは公開または非公開の Symfony Flex recipe repository に公開するためのものです。公開後、bundle の有効化と `config/packages/gsid.yaml` のコピーを利用側 Symfony プロジェクトに対して自動実行できます。

## 6. 確認コマンド

```bash
composer dump-autoload
composer lint
composer test
```
