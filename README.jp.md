# GalacticShrine GsId (PHP)

**言語:** [Français](./README.md) · [English](./README.en.md) · [Español](./README.es.md) · [Italiano](./README.it.md) · **日本語**

256 ビット `GsId` 識別子を生成・解析・検証・変換するための PHP ライブラリです。

## 状態

- バージョン: `1.0.0`
- レベル: 本番運用

## 主なポイント

- `N` / `D` フォーマット
- `Upper` / `Lower` の文字種対応
- `GsIdOptions::configure(...)`
- `GsIdOptions::lock()`
- Symfony ブリッジ同梱
- オプションの Symfony バンドル同梱
- Doctrine DBAL 型同梱

## Symfony バンドル利用時の設定

`config/packages/gsid.yaml`:

```yaml
gsid:
  default_case: Lower
  default_text_format: N
  default_json_format: D
  default_database_format: N
  lock: true
```

`config/bundles.php`:

```php
return [
    GalacticShrine\GsId\Symfony\GsIdBundle::class => ['all' => true],
];
```

## Symfony バンドルなしの設定

`config/services.gsid.yaml` を参照してください。
