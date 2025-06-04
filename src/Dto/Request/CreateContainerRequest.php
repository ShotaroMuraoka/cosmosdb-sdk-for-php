<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class CreateContainerRequest implements RequestDtoInterface
{
    /**
     * @param array<string, mixed> $body
     * @param array<string, string> $headers
     * @param array<string, string> $pathParameters
     */
    public function __construct(
        public array $body = [],
        public array $headers = [],
        public array $pathParameters = [],
    )
    {
        if (empty($this->body['id'])) {
            throw new \InvalidArgumentException('Container id is required.');
        }
        if (empty($this->pathParameters['dbId']) || !isset($this->body['partitionKey'])) {
            throw new \InvalidArgumentException('Database id and partitionKey are required.');
        }
    }
}
