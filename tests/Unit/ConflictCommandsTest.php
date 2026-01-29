<?php

use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteConflictRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetConflictRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListConflictsRequest;
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

describe('Conflict commands', function () {
    it('lists conflicts', function () {
        $result = $this->client->listConflicts(new ListConflictsRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/conflicts')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('gets a conflict', function () {
        $result = $this->client->getConflict(new GetConflictRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'conflictId' => 'conflict1']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/conflicts/conflict1')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('deletes a conflict', function () {
        $result = $this->client->deleteConflict(new DeleteConflictRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll', 'conflictId' => 'conflict1']));

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/conflicts/conflict1')
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
