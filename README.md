# CosmosDB PHP クライアント

Azure Cosmos DB REST API を PHP から扱うための軽量クライアントです。  
Command + DTO 構成で、リクエスト操作を型安全に実行できます。

## インストール

```bash
composer require muraokashotaro/cosmosdb-php
```

## Quick Start

```php
<?php

declare(strict_types=1);

use ShotaroMuraoka\CosmosDb\Auth\MasterKeyAuthStrategy;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateDatabaseRequest;

$endpoint = 'https://<your-account>.documents.azure.com:443/';
$key = '<your-primary-key>';

$client = new CosmosDbClient(new MasterKeyAuthStrategy($endpoint, $key));
$result = $client->createDatabase(new CreateDatabaseRequest('example-db'));
```

## 互換性とバージョニング

- 対応 PHP: `>=8.4`
- SemVer 準拠
- 初回安定化前リリース: `0.1.0`

## パッケージ名とリポジトリ名

- Packagist / Composer package 名: `muraokashotaro/cosmosdb-php`
- GitHub リポジトリ名: `ShotaroMuraoka/cosmosdb-sdk-for-php`

上記 2 つは意図的に異なります。導入時は Composer package 名を使用してください。

## 主要機能

- Database / Container / Document の CRUD
- Query Documents
- Stored Procedures / Triggers / UDFs
- Users / Permissions
- Offers / Conflicts / Change Feed
- 認証方式: Master Key / Resource Token / Azure AD (RBAC)

## 認証方式

### Master Key

```php
use ShotaroMuraoka\CosmosDb\Auth\MasterKeyAuthStrategy;

$auth = new MasterKeyAuthStrategy($endpoint, $key);
```

### Resource Token

```php
use ShotaroMuraoka\CosmosDb\Auth\ResourceTokenAuthStrategy;

$auth = new ResourceTokenAuthStrategy($resourceToken);
```

### Azure AD (RBAC)

```php
use ShotaroMuraoka\CosmosDb\Auth\AzureAdAuthStrategy;

$auth = new AzureAdAuthStrategy($accessToken);
```

## 開発者向け

```bash
composer install
composer analyse
composer test
```

## 品質ゲート

- `composer validate --strict`
- `composer analyse`
- `composer test`

## ライセンス

MIT (`LICENSE`)
