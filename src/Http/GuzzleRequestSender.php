<?php

namespace ShotaroMuraoka\CosmosDb\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use ShotaroMuraoka\CosmosDb\Result\Result;

final class GuzzleRequestSender implements CosmosDbRequestSenderInterface
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => getenv('COSMOSDB_BASE_URI') ?: '','verify' => '/Users/muraokashotaro/emulatorcert.crt',]);
    }

    public function send(
        string $method,
        string $resourcePath,
        string $resourceType,
        array  $headers = [],
        ?array $body = null
    ): Result
    {
        $request = new Request(
            $method,
            $resourcePath,
            $headers,
            json_encode($body),
        );

        try {
            $response = $this->client->send($request);
            $body = json_decode($response->getBody()->getContents(), true);
            return Result::success(
                body: $body ?? [],
                headers: $response->getHeaders(),
                uri: (string) $request->getUri(),
                statusCode: $response->getStatusCode(),
            );
        } catch (RequestException $re) {
            if ($re->hasResponse()) {
                $response = $re->getResponse();
                $statusCode = $response->getStatusCode();
                $message = (string) $response->getBody();
                return Result::failure($message, $statusCode);
            }
            $message = $re->getMessage();
            return Result::failure($message);
        } catch (\Throwable $th) {
            return Result::failure($th->getMessage());
        }
    }
}
