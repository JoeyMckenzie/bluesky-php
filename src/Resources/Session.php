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
    public function __construct(private ConnectorContract $connector)
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function createSession(string $username, string $password): CreateResponse
    {
        $payload = Payload::create('com.atproto.server.createSession', [
            'identifier' => $username,
            'password' => $password,
        ], MediaType::JSON);

        /**
         * @var Response<array{did: string, handle: string, email: string, emailConfirmed: bool, emailAuthFactor: bool, accessJwt: string, refreshJwt: string, active: bool}> $response
         */
        $response = $this->connector->requestData($payload);

        return CreateResponse::from($response->data());
    }
}
