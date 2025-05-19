<?php

namespace Muraokashotaro\CosmosDb;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Muraokashotaro\CosmosDb\Exception\CosmosDbException;
use Muraokashotaro\CosmosDb\Result\Result;

class CosmosDbClient
{
    private string $endpoint;
    private string $key;
    private Client $http;

    private string $typeOfToken;

    public function __construct(
        string           $endpoint,
        string           $key,
        string           $typeOfToken = 'master',
        ?ClientInterface $http = null,
    )
    {
        $this->endpoint = rtrim($endpoint, '/');
        $this->key = $key;
        $this->typeOfToken = $typeOfToken;
        $this->http = $http ?? new Client();
    }

    public function createDatabase(string $dbId): Result
    {
        $request = $this->createHttpRequest('POST', "{$this->endpoint}/dbs/", 'dbs', '', ['id' => $dbId]);
        return $this->sendRequest($request);
    }

    public function deleteDatabase(string $dbId): Result
    {
        $request = $this->createHttpRequest('DELETE', "{$this->endpoint}/dbs/{$dbId}", 'dbs', "dbs/{$dbId}");
        return $this->sendRequest($request);
    }

    public function listDatabases(): Result
    {
        $request = $this->createHttpRequest('GET', "{$this->endpoint}/dbs", 'dbs', '');
        return $this->sendRequest($request);
    }

    public function getDatabase(string $dbId): Result
    {
        $request = $this->createHttpRequest('GET', "{$this->endpoint}/dbs/{$dbId}", 'dbs', "dbs/{$dbId}");
        return $this->sendRequest($request);
    }

    public function createContainer(string $dbId, string $collId): Result
    {
        $request = $this->createHttpRequest('POST', "{$this->endpoint}/dbs/{$dbId}/colls", 'colls', "dbs/{$dbId}", ['id' => $collId, 'partitionKey' => ['paths' => ['/id'], 'kind' => 'Hash', 'Version' => 2]]);
        return $this->sendRequest($request);
    }

    public function listContainers(string $dbId): Result
    {
        $request = $this->createHttpRequest('GET', "{$this->endpoint}/dbs/{$dbId}/colls", 'colls', "dbs/{$dbId}");
        return $this->sendRequest($request);
    }

    public function getContainer(string $dbId, string $collId): Result
    {
        $request = $this->createHttpRequest('GET', "{$this->endpoint}/dbs/{$dbId}/colls/{$collId}", 'colls', "dbs/{$dbId}/colls/{$collId}");
        return $this->sendRequest($request);
    }

    public function deleteContainer(string $dbId, string $collId): Result
    {
        $request = $this->createHttpRequest('DELETE', "{$this->endpoint}/dbs/{$dbId}/colls/{$collId}", 'colls', "dbs/{$dbId}/colls/{$collId}");
        return $this->sendRequest($request);
    }

    public function replaceContainer(string $dbId, string $collId): Result
    {
        $request = $this->createHttpRequest('PUT', "{$this->endpoint}/dbs/{$dbId}/colls/{$collId}", 'colls', "dbs/{$dbId}/colls/{$collId}", [
            'id' => $collId,
            // TODO: partitionKey が必須らしいが、本物を使ってみないとわからない
            // https://learn.microsoft.com/en-us/rest/api/cosmos-db/replace-a-collection
            'partitionKey' => ['paths' => ['/id'], 'kind' => 'Hash', 'Version' => 2],
            'indexingPolicy' => [
                'indexingMode' => 'consistent',
                'automatic' => true,
            ],
        ]);
        return $this->sendRequest($request);
    }

    protected function generateAuthToken(string $verb, string $resourceType, string $resourceLink, string $date): string
    {
        $key = base64_decode($this->key);
        $text = strtolower($verb) . "\n" . strtolower($resourceType) . "\n" . $resourceLink . "\n" . strtolower($date) . "\n\n";
        $signature = base64_encode(hash_hmac('sha256', $text, $key, true));

        return urlencode("type={$this->typeOfToken}&ver=1.0&sig={$signature}");
    }

    protected function createHttpRequest(
        string $method,
        string $url,
        string $resourceType,
        string $resourceLink,
        array  $body = []
    ): Request
    {
        $date = gmdate('D, d M Y H:i:s T');
        $authHeader = $this->generateAuthToken($method, $resourceType, $resourceLink, $date);

        return new Request($method, $url, [
            'Content-Type' => 'application/json',
            'Authorization' => $authHeader,
            'x-ms-version' => '2018-12-31',
            'x-ms-date' => $date,
        ], empty($body) ? null : json_encode($body));
    }

    protected function sendRequest(Request $request): Result
    {
        try {
            $response = $this->http->send($request);
        } catch (GuzzleException $ge) {
            $error = [
                'exception' => $ge,
                'connection_error' => $ge instanceof ConnectException,
                'response' => null,
            ];

            if ($ge instanceof RequestException && $ge->getResponse()) {
                $error['response'] = $ge->getResponse();
            }
            echo $ge->getMessage();
            return new Result($error);
        } catch (\RuntimeException $re) {
            echo $re->getMessage();
            throw new CosmosDbException($re->getMessage(), $re->getCode(), $re);
        }

        return new Result([
            'statusCode' => $response->getStatusCode(),
            'effectiveUri' => $request->getUri(),
            'headers' => $response->getHeaders(),
            'body' => $response->getBody()->getContents(),
        ]);
    }

    public function createDocument(string $db, string $container, array $document): array
    {
//        $resourceType = 'docs';
//        $resourceLink = "dbs/{$db}/colls/{$container}";

        return [];
    }

    public function readDocument(string $db, string $container, string $id): array
    {
        return [];
    }

    public function deleteDocument(string $db, string $container, string $id): bool
    {
        return true;
    }

    public function queryDocuments(string $db, string $container, string $query): array
    {
        return [];
    }
}
