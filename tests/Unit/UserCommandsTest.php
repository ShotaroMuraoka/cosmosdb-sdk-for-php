<?php

use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateUserRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteUserRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetUserRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListUsersRequest;
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

describe('User commands', function () {
    it('creates a user', function () {
        $dto = new CreateUserRequest(['id' => 'alice'], [], ['dbId' => 'mydb']);
        $result = $this->client->createUser($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/users')
            ->and($this->sender->called['body'])->toBe(['id' => 'alice'])
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('lists users', function () {
        $result = $this->client->listUsers(new ListUsersRequest([], [], ['dbId' => 'mydb']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/users')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('retrieves a user', function () {
        $result = $this->client->getUser(new GetUserRequest([], [], ['dbId' => 'mydb', 'userId' => 'alice']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/users/alice')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('deletes a user', function () {
        $result = $this->client->deleteUser(new DeleteUserRequest([], [], ['dbId' => 'mydb', 'userId' => 'alice']));

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/users/alice')
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
