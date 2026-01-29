<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class QueryDocumentsRequest implements RequestDtoInterface
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
        if (empty($this->body['query']) || !is_string($this->body['query'])) {
            throw new \InvalidArgumentException('SQL query string is required');
        }
    }
}
