# GalacticShrine GsId for PHP

**言語:** [Français](./README.md) · [English](./README.en.md) · [Español](./README.es.md) · [Italiano](./README.it.md) · **日本語**

`GsId` は、Galactic-Shrine の **256 ビット**識別子を生成・解析・検証・シリアライズ・保存するための PHP ライブラリです。

純粋な PHP コア、Doctrine DBAL 型、任意の Symfony Bundle、Symfony Bridge、Symfony Flex recipe テンプレートを含みます。

## 概要

| 項目 | 値 |
| --- | --- |
| パッケージ | `galactic-shrine/gsid` |
| メジャーバージョン | `2.0.1` |
| PHP | `>= 8.1` |
| ライセンス | `MPL-2.0` |
| 識別子サイズ | 256 bits / 32 bytes / 64 hexadecimal characters |
| Doctrine 型 | `gsid` |
| Symfony Bundle | `GalacticShrine\GsId\Symfony\GsIdBundle` |

## インストール

```bash
composer require galactic-shrine/gsid:^2.0
```

## 対応フォーマット

`GsId` は 2 つのテキスト形式に対応しています。

### `N` フォーマット

区切り文字なしのコンパクト形式です。

```txt
0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef
```

長さ: `64` 文字。

### `D` フォーマット

区切り文字付きの読みやすい形式です。

```txt
0123456789abcdef-01234567-89abcdef-01234567-89abcdef-0123456789abcdef
```

グループ: `16-8-8-8-8-16`。

長さ: `69` 文字。

## 純粋な PHP での使用

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
    // 有効な識別子です。
}
```

## グローバルオプション

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

`lock()` を呼び出すと、アプリケーション初期化後に設定を変更できなくなります。

## Symfony Bundle を使う場合

Symfony Flex が自動で有効化しない場合は、Bundle を手動で登録します。

```php
// config/bundles.php
return [
    GalacticShrine\GsId\Symfony\GsIdBundle::class => ['all' => true],
];
```

推奨設定:

```yaml
# config/packages/gsid.yaml
gsid:
    default_case: Lower
    default_text_format: N
    default_json_format: D
    default_database_format: N
    lock: true
```

`DoctrineBundle` が存在する場合、Bundle は Doctrine DBAL 型 `gsid` を自動登録します。

## Symfony Bundle なしで使う場合

代替設定として次のファイルを提供しています。

```txt
config/services.gsid.yaml
```

このファイルは次のクラスを手動登録します。

```php
GalacticShrine\GsId\Symfony\GsIdOptionsConfigurator
```

`GsIdBundle` を有効化せずに Symfony 連携を使いたい場合に利用します。

## Doctrine DBAL

`2.0.0` 以降、Doctrine 型は bridge namespace にあります。

```php
use GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType;
```

Entity の例:

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

型名を直接使うこともできます。

```php
#[ORM\Column(type: 'gsid')]
private ?GsId $id = null;
```

## Symfony Bridge

`GsIdToUid` は Symfony の route、DTO、form、serializer 向けの変換を提供します。

```php
use GalacticShrine\GsId\Symfony\Bridge\GsIdToUid;

$value = GsIdToUid::normalizeForRoute($id);
$id = GsIdToUid::denormalizeFromRoute($value);
```

`GsIdToUid` という名前は意図的です。`GsIdToUuid` は誤解を招く可能性があります。`GsId` は 256 ビット識別子であり、標準 UUID は 128 ビット識別子だからです。

## Symfony Flex recipe

recipe テンプレートはここにあります。

```txt
flex-recipes/galactic-shrine/gsid/2.0/
```

この recipe は次のことができます。

- `GsIdBundle` を有効化する;
- `config/packages/gsid.yaml` を利用側 Symfony プロジェクトにコピーする。

重要: このリポジトリ内の `flex-recipes/` はテンプレートです。`composer require` 時に Symfony Flex が自動適用するには、公開または非公開の Symfony Flex recipe repository に公開する必要があります。

## 1.x からの移行

`GsIdDoctrineType` は `2.0.0` で削除されました。

旧:

```php
GalacticShrine\GsId\GsIdDoctrineType
```

新:

```php
GalacticShrine\GsId\Bridge\Doctrine\Types\GsidType
```

詳しくは [UPGRADE-2.0.jp.md](./UPGRADE-2.0.jp.md) を参照してください。

## 開発

```bash
composer install
composer lint
composer test
```

## ライセンス

- 正式ライセンス: [`LICENSE`](LICENSE)
- 日本語ノート: [`LICENSE.jp.md`](LICENSE.jp.md)
- 英語ノート: [`LICENSE.en.md`](LICENSE.en.md)
- フランス語ノート: [`LICENSE.fr.md`](LICENSE.fr.md)
- スペイン語ノート: [`LICENSE.es.md`](LICENSE.es.md)
- イタリア語ノート: [`LICENSE.it.md`](LICENSE.it.md)

GsId is distributed under `MPL-2.0`.
