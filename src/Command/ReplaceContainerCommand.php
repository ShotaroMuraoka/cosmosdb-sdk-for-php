<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\RequestDtoInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;

final class ReplaceContainerCommand implements CommandInterface
{
    private const string METHOD = 'PUT';
    private const string RESOURCE_TYPE = 'colls';
    private const string RESOURCE_LINK = 'dbs/%s/colls/%s';
    private const string ENDPOINT = '/dbs/%s/colls/%s';

    public function __construct(
        private readonly CosmosDbClient $client,
    ) {
    }

    public function execute(RequestDtoInterface $request): Result
    {
        $dbId = $request->pathParameters['dbId'];
        $containerId = $request->pathParameters['collId'];
        $headers = $this->client->authStrategy->getAuthHeaders(
            verb: self::METHOD,
            resourceType: self::RESOURCE_TYPE,
            resourceLink: sprintf(self::RESOURCE_LINK, $dbId, $containerId),
            date: gmdate('D, d M Y H:i:s T'),
        );
        $headers = array_merge($headers, $request->headers);

        return $this->client->sender->send(
            method: self::METHOD,
            resourcePath: sprintf(self::ENDPOINT, $dbId, $containerId),
            headers: $headers,
            body: $request->body
        );
    }
}
