<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class CreateDatabaseRequest implements RequestDtoInterface
{
    /**
     * @param array<string, string> $body
     * @param array<string, string> $headers
     * @param array<string, string> $pathParameters
     */
    public function __construct(
        public readonly array $body = [],
        public readonly array $headers = [],
        public array $pathParameters = [],
    )
    {
        if ($this->body['id'] === '') {
            throw new \InvalidArgumentException('Database id is required');
        }
    }
}
