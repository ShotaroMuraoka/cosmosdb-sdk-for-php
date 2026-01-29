<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class CreateDocumentRequest implements RequestDtoInterface
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
        if (empty($this->pathParameters['dbId']) || empty($this->pathParameters['collId'])) {
            throw new \InvalidArgumentException('Database id and Container id are required');
        }
        if (empty($this->body['id']) || !is_string($this->body['id'])) {
            throw new \InvalidArgumentException('Document id is required');
        }
    }
}
