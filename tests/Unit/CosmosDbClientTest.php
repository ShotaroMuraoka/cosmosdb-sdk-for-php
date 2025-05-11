<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Muraokashotaro\CosmosDb\CosmosDbClient;

beforeEach(function () {
//    $this->client = new CosmosDbClient('https://localhost:80801', 'test-db', 'test-key');
});

//it('create a database', function() {
//    $this->client->createDatabase('test-db');
//    $databases = $this->client->listDatabases();
//    expect($databases)->toContain('test-db');
//});
//
//it('list databases', function() {
//    $databases = $this->client->listDatabases();
//    expect($databases)->toBeArray();
//});
//
//it('get a database', function() {
//    $database = $this->client->getDatabase('test-db');
//    expect($database)->toBeInstanceOf(\Muraokashotaro\CosmosDb\Database::class);
//});
//
//it('delete a database', function() {
//    $this->client->deleteDatabase('test-db');
//    $databases = $this->client->listDatabases();
//    expect($databases)->not()->toContain('test-db');
//});

//it('create a document', function() {
//    $mock = new MockHandler([
//        new Response(201, [], json_encode([
//            'id' => '1',
//            'name' => 'Test Document'
//        ], JSON_THROW_ON_ERROR)),
//    ]);
//
//    $handlerStack = HandlerStack::create($mock);
//    $mockHttpClient = new Client(['handler' => $handlerStack]);
//
//    $client = new CosmosDbClient(
//        'https://localhost:8081',
//        'auth-key',
//        $mockHttpClient,
//    );
//
//    $document = ['id' => '1', 'name' => 'Test Document'];
//    $response = $client->createDocument('db', 'container', $document);
//
//    expect($response)
//        ->toHaveKey('id', '1')
//        ->and($response)->toHaveKey('name', 'Test Document');
//});

it('production create a document', function() {
    $client = new CosmosDbClient(
        'https://localhost:8081',
        'C2y6yDjf5/R+ob0N8A7Cgv30VRDJIWEHLM+4QDU5DE2nQ9nDuVTqobD4b8mGGyPMbIZnqyMsEcaGQy67XIw/Jw==',
    );

    $document = ['id' => '1', 'name' => 'Test Document'];
    $response = $client->createDocument('db', 'container', $document);

    expect($response)
        ->toHaveKey('id', '1')
        ->and($response)->toHaveKey('name', 'Test Document');
});

it('production create database', function() {
    $client = new CosmosDbClient(
        'https://localhost:8081',
        'C2y6yDjf5/R+ob0N8A7Cgv30VRDJIWEHLM+4QDU5DE2nQ9nDuVTqobD4b8mGGyPMbIZnqyMsEcaGQy67XIw/Jw=='
    );

    $response = $client->createDatabase();

    expect($response)
        ->toHaveKey('id', '1')
        ->and($response)->toHaveKey('name', 'Test Document');
});

//it('list documents', function() {
//    $documents = $this->client->listDocuments('test-db', 'test-collection');
//    expect($documents)->toBeArray();
//});
//
//it('get a document', function() {
//    $document = $this->client->getDocument('test-db', 'test-collection', 'test-doc');
//    expect($document)->toBeArray();
//    expect($document['id'])->toBe('test-doc');
//});
//
//it('replace a document', function() {
//    $this->client->replaceDocument('test-db', 'test-collection', 'test-doc', ['id' => 'test-doc', 'name' => 'Updated Document']);
//    $document = $this->client->getDocument('test-db', 'test-collection', 'test-doc');
//    expect($document['name'])->toBe('Updated Document');
//});
//
//it('patch a document', function() {
//    $this->client->patchDocument('test-db', 'test-collection', 'test-doc', ['name' => 'Patched Document']);
//    $document = $this->client->getDocument('test-db', 'test-collection', 'test-doc');
//    expect($document['name'])->toBe('Patched Document');
//});
//
//it('delete a document', function() {
//
//});
//
//it('query documents', function() {
//    $query = 'SELECT * FROM c WHERE c.name = "Patched Document"';
//    $documents = $this->client->queryDocuments('test-db', 'test-collection', $query);
//    expect($documents)->toBeArray();
//    expect($documents[0]['name'])->toBe('Patched Document');
//});
