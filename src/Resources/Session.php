<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\SessionContract;
use Bluesky\Enums\MediaType;
use Bluesky\Responses\Session\CreateResponse;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Override;

final readonly class Session implements SessionContract
{
    public function __construct(
        private ConnectorContract $connector,
        private string $username
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function createSession(string $password): CreateResponse
    {
        $payload = Payload::create('com.atproto.server.createSession', [
            'identifier' => $this->username,
            'password' => $password,
        ], MediaType::JSON);

        /**
         * @var Response<array{did: string, handle: string, email: string, emailConfirmed: bool, emailAuthFactor: bool, accessJwt: string, refreshJwt: string, active: bool}> $response
         */
        $response = $this->connector->requestData($payload);

        return CreateResponse::from($response->data());
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function refreshSession(string $refreshJwt): CreateResponse
    {
        $payload = Payload::create('com.atproto.server.refreshSession', [], MediaType::JSON, [
            'Authorization' => 'Bearer '.$refreshJwt,
        ], false);

        /**
         * @var Response<array{did: string, handle: string, email: null|string, emailConfirmed: null|bool, emailAuthFactor: null|bool, accessJwt: string, refreshJwt: string, active: bool}> $response
         */
        $response = $this->connector->requestData($payload);

        return CreateResponse::from($response->data());
    }
}
