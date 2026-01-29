<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\RequestDtoInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;

final class ReplaceAttachmentCommand implements CommandInterface
{
    private const string METHOD = 'PUT';
    private const string RESOURCE_TYPE = 'attachments';
    private const string RESOURCE_LINK = 'dbs/%s/colls/%s/docs/%s/attachments/%s';
    private const string ENDPOINT = '/dbs/%s/colls/%s/docs/%s/attachments/%s';

    public function __construct(
        private readonly CosmosDbClient $client,
    )
    {
    }

    public function execute(RequestDtoInterface $request): Result
    {
        $dbId = $request->pathParameters['dbId'];
        $collId = $request->pathParameters['collId'];
        $docId = $request->pathParameters['docId'];
        $attachmentId = $request->pathParameters['attachmentId'];
        $headers = $this->client->authStrategy->getAuthHeaders(
            verb: self::METHOD,
            resourceType: self::RESOURCE_TYPE,
            resourceLink: sprintf(self::RESOURCE_LINK, $dbId, $collId, $docId, $attachmentId),
            date: gmdate('D, d M Y H:i:s T'),
        );
        $headers = array_merge($headers, ['Content-Type' => 'application/json'], $request->headers);

        return $this->client->sender->send(
            method: self::METHOD,
            resourcePath: sprintf(self::ENDPOINT, $dbId, $collId, $docId, $attachmentId),
            headers: $headers,
            body: $request->body,
        );
    }
}
