<?php

namespace ShotaroMuraoka\CosmosDb\Http;

use ShotaroMuraoka\CosmosDb\Result\Result;

interface CosmosDbRequestSenderInterface
{
    public function send(
        string $method,
        string $resourcePath,
        string $resourceType,
        array  $headers = [],
        ?array $body = null,
    ): Result;
}
