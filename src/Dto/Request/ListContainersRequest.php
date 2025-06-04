<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class ListContainersRequest implements RequestDtoInterface
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
        if (empty($this->pathParameters['dbId'])) {
            throw new \InvalidArgumentException('Database id is required');
        }
    }
}
