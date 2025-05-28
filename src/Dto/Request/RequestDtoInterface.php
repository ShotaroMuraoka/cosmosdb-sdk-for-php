<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

interface RequestDtoInterface
{
    public array $body {
        get;
    }

    public array $headers {
        get;
    }

    public array $pathParameters {
        get;
    }
}
