<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\RequestDtoInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;

final class GetUserDefinedFunctionCommand implements CommandInterface
{
    private const string METHOD = 'GET';
    private const string RESOURCE_TYPE = 'udfs';
    private const string RESOURCE_LINK = 'dbs/%s/colls/%s/udfs/%s';
    private const string ENDPOINT = '/dbs/%s/colls/%s/udfs/%s';

    public function __construct(
        private readonly CosmosDbClient $client,
    ) {
    }

    public function execute(RequestDtoInterface $request): Result
    {
        $dbId = $request->pathParameters['dbId'];
        $collId = $request->pathParameters['collId'];
        $udfId = $request->pathParameters['udfId'];
        $headers = $this->client->authStrategy->getAuthHeaders(
            verb: self::METHOD,
            resourceType: self::RESOURCE_TYPE,
            resourceLink: sprintf(self::RESOURCE_LINK, $dbId, $collId, $udfId),
            date: gmdate('D, d M Y H:i:s T'),
        );
        $headers = array_merge($headers, $request->headers);

        return $this->client->sender->send(
            method: self::METHOD,
            resourcePath: sprintf(self::ENDPOINT, $dbId, $collId, $udfId),
            headers: $headers,
            body: $request->body,
        );
    }
}
