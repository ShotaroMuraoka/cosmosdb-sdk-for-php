<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class CreateAttachmentRequest implements RequestDtoInterface
{
    /**
     * @param array<string, mixed>  $body
     * @param array<string, string> $headers
     * @param array<string, string> $pathParameters
     */
    public function __construct(
        public readonly array $body = [],
        public readonly array $headers = [],
        public array $pathParameters = [],
    ) {
        if (empty($this->pathParameters['dbId']) || empty($this->pathParameters['collId']) || empty($this->pathParameters['docId'])) {
            throw new \InvalidArgumentException('Database id, Container id and Document id are required');
        }
        if (empty($this->body['id']) || !is_string($this->body['id'])) {
            throw new \InvalidArgumentException('Attachment id is required');
        }
        if (empty($this->body['contentType']) || !is_string($this->body['contentType'])) {
            throw new \InvalidArgumentException('Attachment contentType is required');
        }
        if (empty($this->body['media']) || !is_string($this->body['media'])) {
            throw new \InvalidArgumentException('Attachment media is required');
        }
    }
}
