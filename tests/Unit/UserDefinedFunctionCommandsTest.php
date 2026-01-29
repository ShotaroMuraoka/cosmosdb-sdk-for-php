<?php

use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateUserDefinedFunctionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteUserDefinedFunctionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetUserDefinedFunctionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListUserDefinedFunctionsRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceUserDefinedFunctionRequest;
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

describe('UDF commands', function () {
    it('creates a UDF', function () {
        $body = ['id' => 'uppercase', 'body' => 'function() { return true; }'];
        $dto = new CreateUserDefinedFunctionRequest($body, [], ['dbId' => 'mydb', 'collId' => 'mycoll']);
        $result = $this->client->createUserDefinedFunction($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/udfs')
            ->and($this->sender->called['headers']['Content-Type'])->toBe('application/json')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('lists UDFs', function () {
        $result = $this->client->listUserDefinedFunctions(new ListUserDefinedFunctionsRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/udfs')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('retrieves a UDF', function () {
        $result = $this->client->getUserDefinedFunction(new GetUserDefinedFunctionRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'udfId' => 'uppercase']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/udfs/uppercase')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('replaces a UDF', function () {
        $body = ['id' => 'uppercase', 'body' => 'function() { return false; }'];
        $dto = new ReplaceUserDefinedFunctionRequest($body, [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'udfId' => 'uppercase']);
        $result = $this->client->replaceUserDefinedFunction($dto);

        expect($this->sender->called['method'])->toBe('PUT')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/udfs/uppercase')
            ->and($this->sender->called['headers']['Content-Type'])->toBe('application/json')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('deletes a UDF', function () {
        $result = $this->client->deleteUserDefinedFunction(new DeleteUserDefinedFunctionRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'udfId' => 'uppercase']));

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/udfs/uppercase')
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
