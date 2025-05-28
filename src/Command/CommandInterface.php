<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\Result\Result;
use ShotaroMuraoka\CosmosDb\Dto\Request\RequestDtoInterface;

interface CommandInterface
{
    public function execute(RequestDtoInterface $request): Result;
}
