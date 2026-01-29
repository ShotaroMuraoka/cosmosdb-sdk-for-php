<?php

use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetChangeFeedRequest;
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

describe('Change feed command', function () {
    it('retrieves change feed', function () {
        $result = $this->client->getChangeFeed(new GetChangeFeedRequest([], [], ['dbId' => 'mydb', 'collId' => 'mycoll']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs')
            ->and($this->sender->called['headers']['x-ms-documentdb-change-feed'])->toBe('true')
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
