<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class ListDatabasesRequest implements RequestDtoInterface
{
    /**
     * @param array<string, string> $body
     * @param array<string, string> $headers
     */
    public function __construct(
        public readonly array $body = [],
        public readonly array $headers = [],
        public array $pathParameters = [],
    )
    {
    }
}
