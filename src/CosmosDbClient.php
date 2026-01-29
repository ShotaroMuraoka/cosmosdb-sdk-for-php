<?php

namespace ShotaroMuraoka\CosmosDb;

use GuzzleHttp\Client;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateAttachmentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateDatabaseRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateDocumentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreatePermissionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateStoredProcedureRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateTriggerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateUserDefinedFunctionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateUserRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteAttachmentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteDatabaseRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteDocumentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteOfferRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeletePermissionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteStoredProcedureRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteTriggerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteUserDefinedFunctionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteUserRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteConflictRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ExecuteStoredProcedureRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetChangeFeedRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetConflictRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetDatabaseRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetDocumentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetOfferRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetPartitionKeyRangesForContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetPermissionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetStoredProcedureRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetTriggerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetUserDefinedFunctionRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\GetUserRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListAttachmentsRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListConflictsRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListContainersRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListDatabasesRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListOffersRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListPermissionsRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListStoredProceduresRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListTriggersRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListUserDefinedFunctionsRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListUsersRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\QueryDocumentsRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceAttachmentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceContainerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceDocumentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceOfferRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceStoredProcedureRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceTriggerRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceUserDefinedFunctionRequest;
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
 * @method Result createDocument(CreateDocumentRequest $params)
 * @method Result getDocument(GetDocumentRequest $params)
 * @method Result replaceDocument(ReplaceDocumentRequest $params)
 * @method Result deleteDocument(DeleteDocumentRequest $params)
 * @method Result queryDocuments(QueryDocumentsRequest $params)
 * @method Result createAttachment(CreateAttachmentRequest $params)
 * @method Result listAttachments(ListAttachmentsRequest $params)
 * @method Result replaceAttachment(ReplaceAttachmentRequest $params)
 * @method Result deleteAttachment(DeleteAttachmentRequest $params)
 * @method Result createStoredProcedure(CreateStoredProcedureRequest $params)
 * @method Result listStoredProcedures(ListStoredProceduresRequest $params)
 * @method Result getStoredProcedure(GetStoredProcedureRequest $params)
 * @method Result replaceStoredProcedure(ReplaceStoredProcedureRequest $params)
 * @method Result deleteStoredProcedure(DeleteStoredProcedureRequest $params)
 * @method Result executeStoredProcedure(ExecuteStoredProcedureRequest $params)
 * @method Result createTrigger(CreateTriggerRequest $params)
 * @method Result listTriggers(ListTriggersRequest $params)
 * @method Result getTrigger(GetTriggerRequest $params)
 * @method Result replaceTrigger(ReplaceTriggerRequest $params)
 * @method Result deleteTrigger(DeleteTriggerRequest $params)
 * @method Result createUserDefinedFunction(CreateUserDefinedFunctionRequest $params)
 * @method Result listUserDefinedFunctions(ListUserDefinedFunctionsRequest $params)
 * @method Result getUserDefinedFunction(GetUserDefinedFunctionRequest $params)
 * @method Result replaceUserDefinedFunction(ReplaceUserDefinedFunctionRequest $params)
 * @method Result deleteUserDefinedFunction(DeleteUserDefinedFunctionRequest $params)
 * @method Result createUser(CreateUserRequest $params)
 * @method Result listUsers(ListUsersRequest $params)
 * @method Result getUser(GetUserRequest $params)
 * @method Result deleteUser(DeleteUserRequest $params)
 * @method Result createPermission(CreatePermissionRequest $params)
 * @method Result listPermissions(ListPermissionsRequest $params)
 * @method Result getPermission(GetPermissionRequest $params)
 * @method Result deletePermission(DeletePermissionRequest $params)
 * @method Result listOffers(ListOffersRequest $params)
 * @method Result getOffer(GetOfferRequest $params)
 * @method Result replaceOffer(ReplaceOfferRequest $params)
 * @method Result deleteOffer(DeleteOfferRequest $params)
 * @method Result getChangeFeed(GetChangeFeedRequest $params)
 * @method Result listConflicts(ListConflictsRequest $params)
 * @method Result getConflict(GetConflictRequest $params)
 * @method Result deleteConflict(DeleteConflictRequest $params)
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
