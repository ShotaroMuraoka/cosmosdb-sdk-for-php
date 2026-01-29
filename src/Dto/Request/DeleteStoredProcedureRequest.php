<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class DeleteStoredProcedureRequest implements RequestDtoInterface
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
        if (empty($this->pathParameters['dbId']) || empty($this->pathParameters['collId']) || empty($this->pathParameters['sprocId'])) {
            throw new \InvalidArgumentException('Database id, Container id and Stored Procedure id are required');
        }
    }
}
