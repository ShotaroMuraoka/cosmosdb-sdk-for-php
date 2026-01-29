<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class CreateUserDefinedFunctionRequest implements RequestDtoInterface
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
            throw new \InvalidArgumentException('UDF id is required');
        }
        if (empty($this->body['body']) || !is_string($this->body['body'])) {
            throw new \InvalidArgumentException('UDF body script is required');
        }
    }
}
