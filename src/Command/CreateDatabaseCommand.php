<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\RequestDtoInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;

final class CreateDatabaseCommand implements CommandInterface
{
    private const string METHOD = 'POST';
    private const string RESOURCE_TYPE = 'dbs';
    private const string RESOURCE_LINK = '';
    private const string ENDPOINT = '/dbs/';


    public function __construct(
        private readonly CosmosDbClient $client,
    )
    {
    }

    public function execute(RequestDtoInterface $request): Result
    {
        $headers = $this->client->authStrategy->getAuthHeaders(
            verb: self::METHOD,
            resourceType: self::RESOURCE_TYPE,
            resourceLink: self::RESOURCE_LINK,
            date: gmdate('D, d M Y H:i:s T'),
        );

        $headers = array_merge($headers, $request->headers);

        return $this->client->sender->send(
            method: self::METHOD,
            resourcePath: self::ENDPOINT,
            headers: $headers,
            body: $request->body,
        );
    }
}
