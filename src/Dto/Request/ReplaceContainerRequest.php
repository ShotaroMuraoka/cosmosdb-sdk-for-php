<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class ReplaceContainerRequest implements RequestDtoInterface
{
    /**
     * @param array<string, mixed>  $body    ['dbId' => string, 'collId' => string, ...]
     * @param array<string, string> $headers 追加ヘッダー
     */
    public function __construct(
        public array $body = [],
        public array $headers = [],
        public array $pathParameters = [],
    ) {
        if (empty($this->pathParameters['dbId']) || empty($this->pathParameters['collId'])) {
            throw new \InvalidArgumentException('Database id and Container id are required');
        }
    }
}
