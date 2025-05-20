<?php

namespace ShotaroMuraoka\CosmosDb\Auth;

interface AuthStrategyInterface {
    public function getAuthHeaders(string $verb, string $resourceType, string $resourceLink, string $date): array;
}
