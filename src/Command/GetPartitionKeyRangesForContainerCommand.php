<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Result\Result;

final class GetPartitionKeyRangesForContainerCommand implements CommandInterface
{
    public function __construct(
        private readonly CosmosDbClient $client,
    )
    {
    }

    public function execute(array $params): Result
    {
        $headers = $this->client->authStrategy->getAuthHeaders(
            verb: 'GET',
            resourceType: 'pkranges',
            resourceLink: 'dbs/' . $params['dbId'] . '/colls/' . $params['collId'],
            date: gmdate('D, d M Y H:i:s T'),
        );

        return $this->client->sender->send(
            'GET',
            '/dbs/' . $params['dbId'] . '/colls/' . $params['collId'] . '/pkranges',
            'pkranges',
            $headers,
        );
    }
}
