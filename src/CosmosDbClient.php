<?php

namespace ShotaroMuraoka\CosmosDb;

use GuzzleHttp\Client;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateDatabaseRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteDatabaseRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetDatabaseRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetPartitionKeyRangesForContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListContainersRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListDatabasesRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceContainerRequest;
use ShotaroMuraoka\CosmosDb\Result\Result;
use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\Command\CommandFactory;
use ShotaroMuraoka\CosmosDb\Http\CosmosDbRequestSenderInterface;
use ShotaroMuraoka\CosmosDb\Http\GuzzleRequestSender;

/**
 * @method Result createDatabase(CreateDatabaseRequest $params)
 * @method Result deleteDatabase(DeleteDatabaseRequest $params)
 * @method Result listDatabases(ListDatabasesRequest $params)
 * @method Result getDatabase(GetDatabaseRequest $params)
 * @method Result createContainer(CreateContainerRequest $params)
 * @method Result deleteContainer(DeleteContainerRequest $params)
 * @method Result listContainers(ListContainersRequest $params)
 * @method Result getContainer(GetContainerRequest $params)
 * @method Result replaceContainer(ReplaceContainerRequest $params)
 * @method Result getPartitionKeyRangesForContainer(GetPartitionKeyRangesForContainerRequest $params)
 */
final class CosmosDbClient
{
    public private(set) string $endpoint;
    public private(set) Client $http;
    public private(set) AuthStrategyInterface $authStrategy;
    public private(set) CosmosDbRequestSenderInterface $sender;
    private CommandFactory $commandFactory;

    public function __construct(
        AuthStrategyInterface           $authStrategy,
        ?CosmosDbRequestSenderInterface $sender = null
    )
    {
        $this->sender = $sender ?? new GuzzleRequestSender();
        $this->authStrategy = $authStrategy;
        $this->commandFactory = new CommandFactory($this);
        $this->endpoint = getenv('COSMOSDB_ENDPOINT');
    }

    public function __call(string $name, array $args): Result
    {
        $params = $args[0] ?? [];
        return $this->commandFactory->create($name)->execute($params);
    }
}
