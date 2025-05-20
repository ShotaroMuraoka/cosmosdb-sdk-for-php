<?php

namespace ShotaroMuraoka\CosmosDb\Auth;

final class MasterKeyAuthStrategy implements AuthStrategyInterface
{
    private string $key;
    public function __construct()
    {
        // TODO: キーを取得する場所が適切かどうかを確認する
        $this->key = getenv('COSMOSDB_MASTER_KEY') ?: '';
    }

    public function getAuthHeaders(string $verb, string $resourceType, string $resourceLink, string $date): array
    {
        $key = base64_decode($this->key);
        $text = strtolower($verb) . "\n" . strtolower($resourceType) . "\n" . $resourceLink . "\n" . strtolower($date) . "\n\n";
        $signature = base64_encode(hash_hmac('sha256', $text, $key, true));

        $authorization =  urlencode("type=master&ver=1.0&sig={$signature}");
        return [
            'Content-Type' => 'application/json',
            'Authorization' => $authorization,
            'x-ms-version' => '2018-12-31',
            'x-ms-date' => $date,
        ];
    }
}
