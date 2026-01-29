<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class GetConflictRequest implements RequestDtoInterface
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
        if (empty($this->pathParameters['dbId']) || empty($this->pathParameters['collId']) || empty($this->pathParameters['conflictId'])) {
            throw new \InvalidArgumentException('Database id, Container id and Conflict id are required');
        }
    }
}
