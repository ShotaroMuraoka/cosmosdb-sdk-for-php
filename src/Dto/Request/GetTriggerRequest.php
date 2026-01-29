<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class GetTriggerRequest implements RequestDtoInterface
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
        if (empty($this->pathParameters['dbId']) || empty($this->pathParameters['collId']) || empty($this->pathParameters['triggerId'])) {
            throw new \InvalidArgumentException('Database id, Container id and Trigger id are required');
        }
    }
}
