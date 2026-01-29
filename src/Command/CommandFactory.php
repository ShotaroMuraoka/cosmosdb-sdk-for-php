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
            'getPartitionKeyRangesForContainer' => new GetPartitionKeyRangesForContainerCommand($this->client),
            'createDocument' => new CreateDocumentCommand($this->client),
            'getDocument' => new GetDocumentCommand($this->client),
            'replaceDocument' => new ReplaceDocumentCommand($this->client),
            'deleteDocument' => new DeleteDocumentCommand($this->client),
            'queryDocuments' => new QueryDocumentsCommand($this->client),
            'createAttachment' => new CreateAttachmentCommand($this->client),
            'listAttachments' => new ListAttachmentsCommand($this->client),
            'replaceAttachment' => new ReplaceAttachmentCommand($this->client),
            'deleteAttachment' => new DeleteAttachmentCommand($this->client),
            'createStoredProcedure' => new CreateStoredProcedureCommand($this->client),
            'listStoredProcedures' => new ListStoredProceduresCommand($this->client),
            'getStoredProcedure' => new GetStoredProcedureCommand($this->client),
            'replaceStoredProcedure' => new ReplaceStoredProcedureCommand($this->client),
            'deleteStoredProcedure' => new DeleteStoredProcedureCommand($this->client),
            'executeStoredProcedure' => new ExecuteStoredProcedureCommand($this->client),
            'createTrigger' => new CreateTriggerCommand($this->client),
            'listTriggers' => new ListTriggersCommand($this->client),
            'getTrigger' => new GetTriggerCommand($this->client),
            'replaceTrigger' => new ReplaceTriggerCommand($this->client),
            'deleteTrigger' => new DeleteTriggerCommand($this->client),
            'createUserDefinedFunction' => new CreateUserDefinedFunctionCommand($this->client),
            'listUserDefinedFunctions' => new ListUserDefinedFunctionsCommand($this->client),
            'getUserDefinedFunction' => new GetUserDefinedFunctionCommand($this->client),
            'replaceUserDefinedFunction' => new ReplaceUserDefinedFunctionCommand($this->client),
            'deleteUserDefinedFunction' => new DeleteUserDefinedFunctionCommand($this->client),
            'createUser' => new CreateUserCommand($this->client),
            'listUsers' => new ListUsersCommand($this->client),
            'getUser' => new GetUserCommand($this->client),
            'deleteUser' => new DeleteUserCommand($this->client),
            'createPermission' => new CreatePermissionCommand($this->client),
            'listPermissions' => new ListPermissionsCommand($this->client),
            'getPermission' => new GetPermissionCommand($this->client),
            'deletePermission' => new DeletePermissionCommand($this->client),
            'listOffers' => new ListOffersCommand($this->client),
            'getOffer' => new GetOfferCommand($this->client),
            'replaceOffer' => new ReplaceOfferCommand($this->client),
            'deleteOffer' => new DeleteOfferCommand($this->client),
            'getChangeFeed' => new GetChangeFeedCommand($this->client),
            'listConflicts' => new ListConflictsCommand($this->client),
            'getConflict' => new GetConflictCommand($this->client),
            'deleteConflict' => new DeleteConflictCommand($this->client),
            default => throw new InvalidArgumentException("Unknown command type: $commandType"),
        };
    }
}
