<?php

use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateDocumentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteDocumentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetDocumentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\QueryDocumentsRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceDocumentRequest;
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

describe('Document commands', function () {
    it('creates a document', function () {
        $dto = new CreateDocumentRequest(
            ['id' => 'doc1', 'name' => 'Document 1'],
            [],
            ['dbId' => 'mydb', 'collId' => 'mycoll']
        );
        $result = $this->client->createDocument($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs')
            ->and($this->sender->called['body'])->toBe(['id' => 'doc1', 'name' => 'Document 1'])
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('gets a document', function () {
        $dto = new GetDocumentRequest(body: [], headers: [], pathParameters: ['dbId' => 'mydb', 'collId' => 'mycoll', 'docId' => 'doc1']);
        $result = $this->client->getDocument($dto);

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs/doc1')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('replaces a document', function () {
        $dto = new ReplaceDocumentRequest(
            ['id' => 'doc1', 'name' => 'Updated Document'],
            [],
            ['dbId' => 'mydb', 'collId' => 'mycoll', 'docId' => 'doc1']
        );
        $result = $this->client->replaceDocument($dto);

        expect($this->sender->called['method'])->toBe('PUT')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs/doc1')
            ->and($this->sender->called['body'])->toBe(['id' => 'doc1', 'name' => 'Updated Document'])
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('deletes a document', function () {
        $dto = new DeleteDocumentRequest(body: [], headers: [], pathParameters: ['dbId' => 'mydb', 'collId' => 'mycoll', 'docId' => 'doc1']);
        $result = $this->client->deleteDocument($dto);

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs/doc1')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('queries documents', function () {
        $dto = new QueryDocumentsRequest(
            ['query' => 'SELECT * FROM Families', 'parameters' => []],
            [],
            ['dbId' => 'mydb', 'collId' => 'mycoll']
        );
        $result = $this->client->queryDocuments($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs')
            ->and($this->sender->called['body'])->toBe(['query' => 'SELECT * FROM Families', 'parameters' => []])
            ->and($this->sender->called['headers']['x-ms-documentdb-isquery'])->toBe('True')
            ->and($this->sender->called['headers']['Content-Type'])->toBe('application/query+json')
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
