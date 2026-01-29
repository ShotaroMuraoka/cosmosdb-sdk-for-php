<?php

use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreatePermissionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeletePermissionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetPermissionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListPermissionsRequest;
use ShotaroMuraoka\CosmosDb\Http\CosmosDbRequestSenderInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;

beforeEach(function () {
    $this->sender = new class implements CosmosDbRequestSenderInterface {
        public array $called = [];
        public function send(string $method, string $resourcePath, array $headers = [], ?array $body = null): Result
        {
            $this->called = compact('method', 'resourcePath', 'headers', 'body');
            return Result::success(body: $body, headers: $headers, uri: 'https://localhost' . $resourcePath, statusCode: 200);
        }
    };
    $this->auth = new class implements AuthStrategyInterface {
        public function getAuthHeaders(string $verb, string $resourceType, string $resourceLink, string $date): array
        {
            return [];
        }
    };
    $this->client = new CosmosDbClient($this->auth, $this->sender);
});

describe('Permission commands', function () {
    it('creates a permission', function () {
        $body = [
            'id' => 'readOnly',
            'permissionMode' => 'read',
            'resource' => '/dbs/mydb/colls/mycoll',
            'resourcePartitionKey' => ['mycoll'],
        ];
        $dto = new CreatePermissionRequest($body, [], ['dbId' => 'mydb', 'userId' => 'alice']);
        $result = $this->client->createPermission($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/users/alice/permissions')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($this->sender->called['headers']['Content-Type'])->toBe('application/json')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('lists permissions', function () {
        $result = $this->client->listPermissions(new ListPermissionsRequest([], [], ['dbId' => 'mydb', 'userId' => 'alice']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/users/alice/permissions')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('retrieves a permission', function () {
        $result = $this->client->getPermission(new GetPermissionRequest([], [], ['dbId' => 'mydb', 'userId' => 'alice', 'permissionId' => 'readOnly']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/users/alice/permissions/readOnly')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('deletes a permission', function () {
        $result = $this->client->deletePermission(new DeletePermissionRequest([], [], ['dbId' => 'mydb', 'userId' => 'alice', 'permissionId' => 'readOnly']));

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/users/alice/permissions/readOnly')
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
