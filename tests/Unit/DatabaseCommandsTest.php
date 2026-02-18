<?php

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\Http\CosmosDbRequestSenderInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateDatabaseRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteDatabaseRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListDatabasesRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetDatabaseRequest;

beforeEach(function () {
    $this->sender = new class implements CosmosDbRequestSenderInterface {
        public array $called = [];

        public function send(string $method, string $resourcePath, array $headers = [], ?array $body = null): Result
        {
            $this->called = compact('method', 'resourcePath', 'headers', 'body');
            return Result::success(
                body: $body,
                headers: $headers,
                uri: 'https://localhost' . $resourcePath,
                statusCode: 200
            );
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

describe('Databases', function () {
    it('create a database', function () {
        $dto = new CreateDatabaseRequest(['id' => 'mydb']);
        $result = $this->client->createDatabase($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/')
            ->and($this->sender->called['body'])->toBe(['id' => 'mydb'])
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('list databases', function () {
        $dto = new ListDatabasesRequest();
        $result = $this->client->listDatabases($dto);

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('delete a database', function () {
        $dto = new DeleteDatabaseRequest([], [], ['id' => 'mydb']);
        $result = $this->client->deleteDatabase($dto);

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('get a database', function () {
        $dto = new GetDatabaseRequest([], [], ['id' => 'mydb']);
        $result = $this->client->getDatabase($dto);

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb')
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
