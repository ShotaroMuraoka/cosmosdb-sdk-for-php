<?php

use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteOfferRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetOfferRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListOffersRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceOfferRequest;
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

describe('Offer commands', function () {
    it('lists offers', function () {
        $result = $this->client->listOffers(new ListOffersRequest());

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/offers')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('gets an offer', function () {
        $result = $this->client->getOffer(new GetOfferRequest([], [], ['offerId' => 'offer1']));

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/offers/offer1')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('replaces an offer', function () {
        $dto = new ReplaceOfferRequest([
            'offerResourceId' => 'coll1',
            'offerVersion' => 'V2',
            'content' => ['offerThroughput' => 400],
        ], [], ['offerId' => 'offer1']);
        $result = $this->client->replaceOffer($dto);

        expect($this->sender->called['method'])->toBe('PUT')
            ->and($this->sender->called['resourcePath'])->toBe('/offers/offer1')
            ->and($this->sender->called['body'])->toBe([
                'offerResourceId' => 'coll1',
                'offerVersion' => 'V2',
                'content' => ['offerThroughput' => 400],
            ])
            ->and($this->sender->called['headers']['Content-Type'])->toBe('application/json')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('deletes an offer', function () {
        $result = $this->client->deleteOffer(new DeleteOfferRequest([], [], ['offerId' => 'offer1']));

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/offers/offer1')
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
