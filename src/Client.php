<?php

declare(strict_types=1);

namespace Bluesky;

use Bluesky\Concerns\HasAccessToken;
use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\ATProtoNamespaceContract;
use Bluesky\Contracts\Resources\BskyNamespaceContract;
use Bluesky\Exceptions\AuthenticationException;
use Bluesky\Resources\ATProtoNamespace;
use Bluesky\Resources\BskyNamespace;

/**
 * The primary client gateway for connecting to Bluesky's API containing all connections to the available resources.
 */
final class Client
{
    use HasAccessToken;

    /**
     * The base URL for the Bluesky API, requires authentication by default.
     */
    public const string API_BASE_URL = 'https://bsky.social/xrpc';

    /**
     * The base URL for Bluesky's public view API, does not require authentication.
     */
    public const string PUBLIC_API_BASE_URL = 'https://public.api.bsky.app/xrpc';

    /**
     * Creates a client instance with the provided client transport abstraction.
     */
    public function __construct(
        public readonly ConnectorContract $connector,
        public readonly string $username,
        private ?string $accessJwt = null,
        private ?string $refreshJwt = null)
    {
        //
    }

    public function newSession(string $password): self
    {
        $newSession = $this->atproto()->server()->createSession($password);
        $this->accessJwt = $newSession->accessJwt;
        $this->refreshJwt = $newSession->refreshJwt;

        return $this;
    }

    public function atproto(): ATProtoNamespaceContract
    {
        return new ATProtoNamespace($this->connector, $this->username);
    }

    public function bsky(): BskyNamespaceContract
    {
        return new BskyNamespace($this->connector, $this->username, $this->accessJwt);
    }

    /**
     * @throws AuthenticationException
     */
    public function refreshSession(): self
    {
        if ($this->refreshJwt === null) {
            throw new AuthenticationException('Refresh JWT is required to refresh a session.');
        }

        $refreshedSession = $this->atproto()->server()->refreshSession($this->refreshJwt);
        $this->accessJwt = $refreshedSession->accessJwt;
        $this->refreshJwt = $refreshedSession->refreshJwt;

        return $this;
    }

    public function getRefreshJwt(): ?string
    {
        return $this->refreshJwt;
    }
}
