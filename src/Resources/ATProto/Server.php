<?php

declare(strict_types=1);

namespace Bluesky\Resources\ATProto;

use Bluesky\Concerns\HasUserContext;
use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\ATProto\ServerContract;
use Bluesky\Contracts\Resources\ResourceNamespaceContract;
use Bluesky\Enums\MediaType;
use Bluesky\Exceptions\AuthenticationException;
use Bluesky\Responses\ATProto\Session\CreateSessionResponse;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Override;

final readonly class Server implements ResourceNamespaceContract, ServerContract
{
    use HasUserContext;

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

        $payload = Payload::post($this->getNamespace().'.createSession', [
            'identifier' => $this->username,
            'password' => $password,
        ], MediaType::JSON);

        /**
         * @var Response<array{did: string, handle: string, email: string, emailConfirmed: bool, emailAuthFactor: bool, accessJwt: string, refreshJwt: string, active: bool}> $response
         */
        $response = $this->connector->requestData($payload);

        return CreateSessionResponse::from($response->data());
    }

    #[Override]
    public function getNamespace(): string
    {
        return 'com.atproto.server';
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
            $this->getNamespace().'.refreshSession',
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
