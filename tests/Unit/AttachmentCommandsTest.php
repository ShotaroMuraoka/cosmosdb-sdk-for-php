<?php

use ShotaroMuraoka\CosmosDb\Auth\AuthStrategyInterface;
use ShotaroMuraoka\CosmosDb\CosmosDbClient;
use ShotaroMuraoka\CosmosDb\Dto\Request\CreateAttachmentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\DeleteAttachmentRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ListAttachmentsRequest;
use ShotaroMuraoka\CosmosDb\Dto\Request\ReplaceAttachmentRequest;
use ShotaroMuraoka\CosmosDb\Http\CosmosDbRequestSenderInterface;
use ShotaroMuraoka\CosmosDb\Result\Result;

beforeEach(function () {
    $this->sender = new class implements CosmosDbRequestSenderInterface {
        public array $called = [];

        public function send(string $method, string $resourcePath, array $headers = [], ?array $body = null): Result
        {
            $this->called = compact('method', 'resourcePath', 'headers', 'body');
            return Result::success(
                body: $body,
                headers: $headers,
                uri: 'https://localhost' . $resourcePath,
                statusCode: 200
            );
        }
    };

    $this->auth = new class implements AuthStrategyInterface {
        public function getAuthHeaders(string $verb, string $resourceType, string $resourceLink, string $date): array
        {
            return [];
        }
    };

    $this->client = new CosmosDbClient($this->auth, $this->sender);
});

describe('Attachment commands', function () {
    it('creates an attachment', function () {
        $body = ['id' => 'att1', 'contentType' => 'text/plain', 'media' => base64_encode('data')];
        $dto = new CreateAttachmentRequest(
            $body,
            [],
            ['dbId' => 'mydb', 'collId' => 'mycoll', 'docId' => 'doc1']
        );
        $result = $this->client->createAttachment($dto);

        expect($this->sender->called['method'])->toBe('POST')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs/doc1/attachments')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('lists attachments', function () {
        $dto = new ListAttachmentsRequest(
            [],
            [],
            ['dbId' => 'mydb', 'collId' => 'mycoll', 'docId' => 'doc1']
        );
        $result = $this->client->listAttachments($dto);

        expect($this->sender->called['method'])->toBe('GET')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs/doc1/attachments')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('replaces an attachment', function () {
        $body = ['id' => 'att1', 'contentType' => 'image/png', 'media' => base64_encode('bytes')];
        $dto = new ReplaceAttachmentRequest(
            $body,
            [],
            ['dbId' => 'mydb', 'collId' => 'mycoll', 'docId' => 'doc1', 'attachmentId' => 'att1']
        );
        $result = $this->client->replaceAttachment($dto);

        expect($this->sender->called['method'])->toBe('PUT')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs/doc1/attachments/att1')
            ->and($this->sender->called['body'])->toBe($body)
            ->and($this->sender->called['headers']['Content-Type'])->toBe('application/json')
            ->and($result)->toBeInstanceOf(Result::class);
    });

    it('deletes an attachment', function () {
        $dto = new DeleteAttachmentRequest(
            [],
            [],
            ['dbId' => 'mydb', 'collId' => 'mycoll', 'docId' => 'doc1', 'attachmentId' => 'att1']
        );
        $result = $this->client->deleteAttachment($dto);

        expect($this->sender->called['method'])->toBe('DELETE')
            ->and($this->sender->called['resourcePath'])->toBe('/dbs/mydb/colls/mycoll/docs/doc1/attachments/att1')
            ->and($result)->toBeInstanceOf(Result::class);
    });
});
