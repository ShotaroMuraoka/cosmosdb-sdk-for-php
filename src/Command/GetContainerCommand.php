<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Result\Result;
use ShotaroMuraoka\CosmosDb\Dto\Request\RequestDtoInterface;

final class GetContainerCommand implements CommandInterface
{
    private const string METHOD = 'GET';
    private const string RESOURCE_TYPE = 'colls';
    private const string RESOURCE_LINK = 'dbs/%s/colls/%s';
    private const string ENDPOINT = '/dbs/%s/colls/%s';

    public function __construct(
        private readonly CosmosDbClient $client,
    )
    {
    }

    public function execute(RequestDtoInterface $request): Result
    {
        $pathParams = $request->pathParameters;
        $dbId = $pathParams['dbId'];
        $collId = $pathParams['collId'];
        $headers = $this->client->authStrategy->getAuthHeaders(
            verb: self::METHOD,
            resourceType: self::RESOURCE_TYPE,
            resourceLink: sprintf(self::RESOURCE_LINK, $dbId, $collId),
            date: gmdate('D, d M Y H:i:s T'),
        );
        $headers = array_merge($headers, $request->headers);

        return $this->client->sender->send(
            method: self::METHOD,
            resourcePath: sprintf(self::ENDPOINT, $dbId, $collId),
            headers: $headers,
            body: $request->body
        );
    }
}
