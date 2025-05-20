<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use InvalidArgumentException;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;

final class CommandFactory
{
    public function __construct(
        private CosmosDbClient $client,
    )
    {
    }

    public function create(string $commandType): CommandInterface
    {
        return match ($commandType) {
            'createDatabase' => new CreateDatabaseCommand($this->client),
            'deleteDatabase' => new DeleteDatabaseCommand($this->client),
            'listDatabases' => new ListDatabasesCommand($this->client),
            'getDatabase' => new GetDatabaseCommand($this->client),
            'createContainer' => new CreateContainerCommand($this->client),
            'deleteContainer' => new DeleteContainerCommand($this->client),
            'listContainers' => new ListContainersCommand($this->client),
            'getContainer' => new GetContainerCommand($this->client),
            'replaceContainer' => new ReplaceContainerCommand($this->client),
            default => throw new InvalidArgumentException("Unknown command type: $commandType"),
        };
    }
}
