<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Result\Result;

final class DeleteDatabaseCommand implements CommandInterface
{
    public function __construct(
        private readonly CosmosDbClient $client,
    )
    {
    }

    public function execute(array $params): Result
    {
        $headers = $this->client->authStrategy->getAuthHeaders(
            verb: 'DELETE',
            resourceType: 'dbs',
            resourceLink: 'dbs/' . $params['id'],
            date: gmdate('D, d M Y H:i:s T'),
        );

        return $this->client->sender->send(
            'DELETE',
            '/dbs/' . $params['id'],
            'dbs',
            $headers,
            $params,
        );
    }
}