<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\RequestDtoInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;

final class GetPartitionKeyRangesForContainerCommand implements CommandInterface
{
    public function __construct(
        private readonly CosmosDbClient $client,
    ) {
    }

    public function execute(RequestDtoInterface $request): Result
    {
        $dbId = $request->pathParameters['dbId'];
        $collId = $request->pathParameters['collId'];
        $headers = $this->client->authStrategy->getAuthHeaders(
            verb: 'GET',
            resourceType: 'pkranges',
            resourceLink: "dbs/{$dbId}/colls/{$collId}",
            date: gmdate('D, d M Y H:i:s T'),
        );
        $headers = array_merge($headers, $request->headers);

        return $this->client->sender->send(
            'GET',
            "/dbs/{$dbId}/colls/{$collId}/pkranges",
            $headers,
            $request->body,
        );
    }
}
