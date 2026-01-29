<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\RequestDtoInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;

final class ReplaceOfferCommand implements CommandInterface
{
    private const string METHOD = 'PUT';
    private const string RESOURCE_TYPE = 'offers';
    private const string RESOURCE_LINK = 'offers/%s';
    private const string ENDPOINT = '/offers/%s';

    public function __construct(
        private readonly CosmosDbClient $client,
    ) {
    }

    public function execute(RequestDtoInterface $request): Result
    {
        $offerId = $request->pathParameters['offerId'];
        $headers = $this->client->authStrategy->getAuthHeaders(
            verb: self::METHOD,
            resourceType: self::RESOURCE_TYPE,
            resourceLink: sprintf(self::RESOURCE_LINK, $offerId),
            date: gmdate('D, d M Y H:i:s T'),
        );
        $headers = array_merge($headers, ['Content-Type' => 'application/json'], $request->headers);

        return $this->client->sender->send(
            method: self::METHOD,
            resourcePath: sprintf(self::ENDPOINT, $offerId),
            headers: $headers,
            body: $request->body,
        );
    }
}
