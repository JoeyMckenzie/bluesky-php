<?php

declare(strict_types=1);

namespace Bluesky;

use Bluesky\Concerns\HasAccessToken;
use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\ActorContract;
use Bluesky\Contracts\Resources\FeedContract;
use Bluesky\Contracts\Resources\SessionContract;
use Bluesky\Exceptions\AuthenticationException;
use Bluesky\Resources\Actor;
use Bluesky\Resources\Feed;
use Bluesky\Resources\Session;

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
        $newSession = $this->session()->createSession($password);
        $this->accessJwt = $newSession->accessJwt;
        $this->refreshJwt = $newSession->refreshJwt;

        return $this;
    }

    public function session(): SessionContract
    {
        return new Session($this->connector, $this->username);
    }

    /**
     * @throws AuthenticationException
     */
    public function refreshSession(): self
    {
        if ($this->refreshJwt === null) {
            throw new AuthenticationException('Refresh JWT is required to refresh a session.');
        }

        $refreshedSession = $this->session()->refreshSession($this->refreshJwt);
        $this->accessJwt = $refreshedSession->accessJwt;
        $this->refreshJwt = $refreshedSession->refreshJwt;

        return $this;
    }

    public function actor(): ActorContract
    {
        return new Actor($this->connector, $this->accessJwt);
    }

    public function feed(): FeedContract
    {
        return new Feed($this->connector, $this->username, $this->accessJwt);
    }

    public function getRefreshJwt(): ?string
    {
        return $this->refreshJwt;
    }
}
