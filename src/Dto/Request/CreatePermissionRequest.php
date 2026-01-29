<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class CreatePermissionRequest implements RequestDtoInterface
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
        if (empty($this->pathParameters['dbId']) || empty($this->pathParameters['userId'])) {
            throw new \InvalidArgumentException('Database id and User id are required');
        }
        if (empty($this->body['id']) || !is_string($this->body['id'])) {
            throw new \InvalidArgumentException('Permission id is required');
        }
        if (empty($this->body['permissionMode']) || !is_string($this->body['permissionMode'])) {
            throw new \InvalidArgumentException('Permission mode is required');
        }
        if (empty($this->body['resource']) || !is_string($this->body['resource'])) {
            throw new \InvalidArgumentException('Resource link is required');
        }
        if (!array_key_exists('resourcePartitionKey', $this->body) || !is_array($this->body['resourcePartitionKey'])) {
            throw new \InvalidArgumentException('Resource partition key array is required');
        }
    }
}
