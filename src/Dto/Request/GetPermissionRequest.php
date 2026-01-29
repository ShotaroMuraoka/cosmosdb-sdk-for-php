<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class GetPermissionRequest implements RequestDtoInterface
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
        if (empty($this->pathParameters['dbId']) || empty($this->pathParameters['userId']) || empty($this->pathParameters['permissionId'])) {
            throw new \InvalidArgumentException('Database id, User id and Permission id are required');
        }
    }
}
