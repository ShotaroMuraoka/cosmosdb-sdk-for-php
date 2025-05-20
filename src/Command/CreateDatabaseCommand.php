<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Result\Result;

final class CreateDatabaseCommand implements CommandInterface
{
    public function __construct(
        private readonly CosmosDbClient $client,
    )
    {
    }

    public function execute(array $params): Result
    {
        $headers = $this->client->authStrategy->getAuthHeaders(
            verb: 'POST',
            resourceType: 'dbs',
            resourceLink: '',
            date: gmdate('D, d M Y H:i:s T'),
        );

        return $this->client->sender->send(
            'POST',
            '/dbs/',
            'dbs',
            $headers,
            $params,
        );
    }
}
