<?php

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\Http\CosmosDbRequestSenderInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListContainersRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetPartitionKeyRangesForContainerRequest;

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

describe('Containers', function () {
    it('creates a container', function () {
        $body = ['id' => 'cont', 'partitionKey' => ['/pk']];
        $pathParameters = ['dbId' => 'mydb'];
        $dto = new CreateContainerRequest($body, [], $pathParameters);
        $result = $this->client->createContainer($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('lists containers', function () {
        $dto = new ListContainersRequest([], [], ['dbId' => 'mydb']);
        $result = $this->client->listContainers($dto);

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('deletes a container', function () {
        $dto = new DeleteContainerRequest(pathParameters: ['dbId' => 'cont', 'collId' => 'mycoll']);
        $result = $this->client->deleteContainer($dto);

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/cont/colls/mycoll')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('gets a container', function () {
        $dto = new GetContainerRequest(pathParameters: ['dbId' => 'cont', 'collId' => 'mycoll']);
        $result = $this->client->getContainer($dto);

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/cont/colls/mycoll')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('replaces a container', function () {
        $body = ['id' => 'cont', 'partitionKey' => ['']];
        $dto = new ReplaceContainerRequest(
            pathParameters: ['dbId' => 'cont', 'collId' => 'mycoll'],
            body: $body
        );
        $result = $this->client->replaceContainer($dto);

        expect($this->sender->called['method'])->toBe('PUT')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/cont/colls/mycoll')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('gets partition key ranges for a container', function () {
        $dto = new GetPartitionKeyRangesForContainerRequest(pathParameters: ['dbId' => 'cont', 'collId' => 'mycoll']);
        $result = $this->client->getPartitionKeyRangesForContainer($dto);

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/cont/colls/mycoll/pkranges')
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
