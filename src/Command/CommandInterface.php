<?php

namespace ShotaroMuraoka\CosmosDb\Command;

use ShotaroMuraoka\CosmosDb\Result\Result;

interface CommandInterface
{
    public function execute(array $params): Result;
}
