<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\SessionContract;
use Bluesky\Enums\MediaType;
use Bluesky\Exceptions\AuthenticationException;
use Bluesky\Responses\Session\CreateSessionResponse;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Override;

final readonly class Session implements SessionContract
{
    private const string BEARER_PREFIX = 'Bearer ';

    public function __construct(
        private ConnectorContract $connector,
        private string $username
    ) {
        //
    }

    /**
     * {@inheritDoc}
     *
     * @throws AuthenticationException
     */
    #[Override]
    public function createSession(string $password): CreateSessionResponse
    {
        if ($password === '') {
            throw new AuthenticationException('Password cannot be empty.');
        }

        $payload = Payload::post('com.atproto.server.createSession', [
            'identifier' => $this->username,
            'password' => $password,
        ], MediaType::JSON);

        /**
         * @var Response<array{did: string, handle: string, email: string, emailConfirmed: bool, emailAuthFactor: bool, accessJwt: string, refreshJwt: string, active: bool}> $response
         */
        $response = $this->connector->requestData($payload);

        return CreateSessionResponse::from($response->data());
    }

    /**
     * {@inheritDoc}
     *
     * @throws AuthenticationException
     */
    #[Override]
    public function refreshSession(string $refreshJwt): CreateSessionResponse
    {
        if ($refreshJwt === '') {
            throw new AuthenticationException('Refresh token cannot be empty.');
        }

        $authHeader = self::BEARER_PREFIX.$refreshJwt;
        $payload = Payload::post(
            'com.atproto.server.refreshSession',
            [],
            MediaType::JSON,
            ['Authorization' => $authHeader],
            false
        );

        /**
         * @var Response<array{did: string, handle: string, email: null|string, emailConfirmed: null|bool, emailAuthFactor: null|bool, accessJwt: string, refreshJwt: string, active: bool}> $response
         */
        $response = $this->connector->requestData($payload);

        return CreateSessionResponse::from($response->data());
    }
}
