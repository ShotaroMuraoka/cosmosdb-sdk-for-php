<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class ReplaceTriggerRequest implements RequestDtoInterface
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
        if (empty($this->body['id']) || !is_string($this->body['id'])) {
            throw new \InvalidArgumentException('Trigger id is required');
        }
        if (empty($this->body['triggerType']) || !is_string($this->body['triggerType'])) {
            throw new \InvalidArgumentException('Trigger type is required');
        }
        if (empty($this->body['triggerOperation']) || !is_string($this->body['triggerOperation'])) {
            throw new \InvalidArgumentException('Trigger operation is required');
        }
        if (empty($this->body['body']) || !is_string($this->body['body'])) {
            throw new \InvalidArgumentException('Trigger body is required');
        }
    }
}
