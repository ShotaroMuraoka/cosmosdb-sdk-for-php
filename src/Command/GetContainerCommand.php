<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Result\Result;
use ShotaroMuraoka\CosmosDb\Dto\Request\RequestDtoInterface;

final class GetContainerCommand implements CommandInterface
{
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
            verb: 'GET',
            resourceType: 'colls',
            resourceLink: "dbs/{$dbId}/colls/{$collId}",
            date: gmdate('D, d M Y H:i:s T'),
        );
        $headers = array_merge($headers, $request->headers);

        return $this->client->sender->send(
            'GET',
            "/dbs/{$dbId}/colls/{$collId}",
            $headers,
            $request->body
        );
    }
}
