<?php

namespace ShotaroMuraoka\CosmosDb\Result;

final class Result
{

    private function __construct(
        public readonly bool    $isSuccess,
        public readonly ?array  $body,
        public readonly ?array  $headers,
        public readonly ?string $uri,
        public readonly ?int    $statusCode,
        public readonly ?string $errorMessage,
    )
    {
    }

    public static function success(array $body, array $headers, string $uri, int $statusCode): self
    {
        return new self(
            isSuccess: true,
            body: $body,
            headers: $headers,
            uri: $uri,
            statusCode: $statusCode,
            errorMessage: null,
        );
    }

    public static function failure(?string $errorMessage, ?int $statusCode = null): self
    {
        return new self(
            isSuccess: false,
            body: null,
            headers: null,
            uri: null,
            statusCode: $statusCode,
            errorMessage: $errorMessage,
        );
    }
}
