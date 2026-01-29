<?php

namespace ShotaroMuraoka\CosmosDb\Dto\Request;

final class ReplaceOfferRequest implements RequestDtoInterface
{
    /**
     * @param array<string, mixed>  $body
     * @param array<string, string> $headers
     * @param array<string, string> $pathParameters
     */
    public function __construct(
        public readonly array $body = [],
        public readonly array $headers = [],
        public array $pathParameters = [],
    ) {
        if (empty($this->pathParameters['offerId'])) {
            throw new \InvalidArgumentException('Offer id is required');
        }
        if (empty($this->body['offerResourceId']) || !is_string($this->body['offerResourceId'])) {
            throw new \InvalidArgumentException('offerResourceId is required');
        }
        if (empty($this->body['offerVersion']) || !is_string($this->body['offerVersion'])) {
            throw new \InvalidArgumentException('offerVersion is required');
        }
        if (empty($this->body['content']) || !is_array($this->body['content'])) {
            throw new \InvalidArgumentException('offer content is required');
        }
    }
}
