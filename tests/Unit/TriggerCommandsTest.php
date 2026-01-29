<?php

use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateTriggerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteTriggerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetTriggerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListTriggersRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceTriggerRequest;
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

describe('Trigger commands', function () {
    it('creates a trigger', function () {
        $body = ['id' => 'preInsert', 'triggerType' => 'Pre', 'triggerOperation' => 'All', 'body' => 'function() { }'];
        $dto = new CreateTriggerRequest($body, [], ['dbId' => 'mydb', 'collId' => 'mycoll']);
        $result = $this->client->createTrigger($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/triggers')
            ->and($this->sender->called['headers']['Content-Type'])->toBe('application/json')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('lists triggers', function () {
        $result = $this->client->listTriggers(new ListTriggersRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/triggers')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('gets a trigger', function () {
        $result = $this->client->getTrigger(new GetTriggerRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'triggerId' => 'preInsert']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/triggers/preInsert')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('replaces a trigger', function () {
        $body = ['id' => 'preInsert', 'triggerType' => 'Pre', 'triggerOperation' => 'All', 'body' => 'function() { return true; }'];
        $dto = new ReplaceTriggerRequest($body, [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'triggerId' => 'preInsert']);
        $result = $this->client->replaceTrigger($dto);

        expect($this->sender->called['method'])->toBe('PUT')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/triggers/preInsert')
            ->and($this->sender->called['headers']['Content-Type'])->toBe('application/json')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('deletes a trigger', function () {
        $result = $this->client->deleteTrigger(new DeleteTriggerRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'triggerId' => 'preInsert']));

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/triggers/preInsert')
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
