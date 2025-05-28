<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class CreateContainerRequest implements RequestDtoInterface
{
    /**
     * @param array<string, mixed>  $body
     * @param array<string, string> $headers
     * @param array<string, string> $pathParameters
     */
    public function __construct(
        public array $body = [],
        public array $headers = [],
        public array $pathParameters = [],
    ) {
        if (empty($this->pathParameters['dbId']) || empty($this->body['id']) || !isset($this->body['partitionKey'])) {
            throw new \InvalidArgumentException('dbId, id and partitionKey are required.');
        }
    }
}
