<?php

namespace ShotaroMuraoka\CosmosDb;

use GuzzleHttp\Client;
use ShotaroMuraoka\CosmosDb\Result\Result;
use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\Command\CommandFactory;
use ShotaroMuraoka\CosmosDb\Http\CosmosDbRequestSenderInterface;
use ShotaroMuraoka\CosmosDb\Http\GuzzleRequestSender;

/**
 * @method Result createDatabase(array $params)
 * @method Result deleteDatabase(array $params)
 * @method Result listDatabases(array $params)
 * @method Result getDatabase(array $params)
 * @method Result createContainer(array $params)
 * @method Result deleteContainer(array $params)
 * @method Result listContainers(array $params)
 * @method Result getContainer(array $params)
 * @method Result replaceContainer(array $params)
 * @method Result getPartitionKeyRangesForContainer(array $params)
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
