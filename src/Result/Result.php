<?php

namespace Muraokashotaro\CosmosDb\Result;
final class Result {

    public function __construct(private(set) array $data = [])
    {
    }
}