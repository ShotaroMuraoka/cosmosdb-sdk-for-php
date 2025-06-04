<?php

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\Http\CosmosDbRequestSenderInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateDatabaseRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteDatabaseRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListDatabasesRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListContainersRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetDatabaseRequest;
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

describe('Containers', function () {
    it('creates a container', function () {
        $body = ['id' => 'cont', 'partitionKey' => ['/pk']];
        $header = [];
        $pathParameters = ['dbId' => 'mydb'];
        $dto = new CreateContainerRequest($body, $header, $pathParameters);
        $result = $this->client->createContainer($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('lists containers', function () {
        $body = [];
        $header = [];
        $pathParameters = ['dbId' => 'mydb'];
        $dto = new ListContainersRequest($body, $header, $pathParameters);
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

describe('Documents', function () {
    it('creates a document', function () {
        $body = ['id' => 'doc1', 'name' => 'Document 1'];
        $header = [];
        $pathParameters = ['dbId' => 'mydb', 'collId' => 'mycoll'];
        $dto = new CreateDocumentRequest($body, $header, $pathParameters);
        $result = $this->client->createDocument($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('lists documents', function () {
        $body = [];
        $header = [];
        $pathParameters = ['dbId' => 'mydb', 'collId' => 'mycoll'];
        $dto = new ListDocumentsRequest($body, $header, $pathParameters);
        $result = $this->client->listDocuments($dto);

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('get a document', function () {
        $dto = new GetDocumentRequest(pathParameters: ['dbId' => 'mydb', 'collId' => 'mycoll', 'docId' => 'doc1']);
        $result = $this->client->getDocument($dto);

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs/doc1')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('replace a document', function () {
        $body = ['id' => 'doc1', 'name' => 'Updated Document'];
        $dto = new ReplaceDocumentRequest(
            pathParameters: ['dbId' => 'mydb', 'collId' => 'mycoll', 'docId' => 'doc1'],
            body: $body
        );
        $result = $this->client->replaceDocument($dto);

        expect($this->sender->called['method'])->toBe('PUT')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs/doc1')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('patch a document', function () {
        $body = [
            'operations' => [
                'op' => 'set',
                'path' => '/Parents/0/Name',
                'value' => 'Patched Document'
            ]
        ];
        $dto = new PatchDocumentRequest(
            pathParameters: ['dbId' => 'mydb', 'collId' => 'mycoll', 'docId' => 'doc1'],
            body: $body
        );
        $result = $this->client->patchDocument($dto);

        expect($this->sender->called['method'])->toBe('PATCH')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs/doc1')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('delete a document', function () {
        $dto = new DeleteDocumentRequest(pathParameters: ['dbId' => 'mydb', 'collId' => 'mycoll', 'docId' => 'doc1']);
        $result = $this->client->deleteDocument($dto);

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs/doc1')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('query documents', function () {
        $body = [
            'query' => 'SELECT * FROM Families f WHERE f.LastName = "Andersen"',
            'parameters' => []
        ];
        $header = [];
        $pathParameters = ['dbId' => 'mydb', 'collId' => 'mycoll'];
        $dto = new QueryDocumentsRequest($body, $header, $pathParameters);
        $result = $this->client->queryDocuments($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
