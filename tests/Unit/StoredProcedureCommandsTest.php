<?php

use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateStoredProcedureRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteStoredProcedureRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ExecuteStoredProcedureRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetStoredProcedureRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListStoredProceduresRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceStoredProcedureRequest;
use ShotaroMuraoka\CosmosDb\Http\CosmosDbRequestSenderInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;

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

describe('Stored procedure commands', function () {
    it('creates a stored procedure', function () {
        $dto = new CreateStoredProcedureRequest([
            'id' => 'calc',
            'body' => 'function() { return 1; }',
        ], [], ['dbId' => 'mydb', 'collId' => 'mycoll']);
        $result = $this->client->createStoredProcedure($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/sprocs')
            ->and($this->sender->called['headers']['Content-Type'])->toBe('application/json')
            ->and($this->sender->called['body'])->toBe([
                'id' => 'calc',
                'body' => 'function() { return 1; }',
            ])
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('lists stored procedures', function () {
        $result = $this->client->listStoredProcedures(new ListStoredProceduresRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/sprocs')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('retrieves a stored procedure', function () {
        $result = $this->client->getStoredProcedure(new GetStoredProcedureRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'sprocId' => 'calc']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/sprocs/calc')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('replaces a stored procedure', function () {
        $body = ['id' => 'calc', 'body' => 'function() { return 2; }'];
        $dto = new ReplaceStoredProcedureRequest($body, [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'sprocId' => 'calc']);
        $result = $this->client->replaceStoredProcedure($dto);

        expect($this->sender->called['method'])->toBe('PUT')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/sprocs/calc')
            ->and($this->sender->called['headers']['Content-Type'])->toBe('application/json')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('deletes a stored procedure', function () {
        $result = $this->client->deleteStoredProcedure(new DeleteStoredProcedureRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'sprocId' => 'calc']));

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/sprocs/calc')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('executes a stored procedure', function () {
        $dto = new ExecuteStoredProcedureRequest(['parameters' => ['hello', 1]], [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'sprocId' => 'calc']);
        $result = $this->client->executeStoredProcedure($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/sprocs/calc')
            ->and($this->sender->called['headers']['Content-Type'])->toBe('application/json')
            ->and($this->sender->called['body'])->toBe(['hello', 1])
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
