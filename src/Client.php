<?php

declare(strict_types=1);

namespace Bluesky;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\ActorContract;
use Bluesky\Contracts\Resources\FeedContract;
use Bluesky\Contracts\Resources\SessionContract;
use Bluesky\Exceptions\AuthenticationTokenException;
use Bluesky\Resources\Actor;
use Bluesky\Resources\Feed;
use Bluesky\Resources\Session;

/**
 * The primary client gateway for connecting to Open Brewery DB's API containing all connections to the available resources.
 */
final class Client
{
    /**
     * The base URL for Open Brewery DB API.
     */
    public const string API_BASE_URL = 'https://bsky.social/xrpc';

    /**
     * The base URL for Open Brewery DB API.
     */
    public const string PUBLIC_API_BASE_URL = 'https://public.api.bsky.app/xrpc';

    /**
     * Creates a client instance with the provided client transport abstraction.
     */
    public function __construct(
        private readonly ConnectorContract $connector,
        public readonly string $username,
        public ?string $accessJwt = null,
        public ?string $refreshJwt = null)
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
     * @throws AuthenticationTokenException
     */
    public function refreshSession(): self
    {
        if ($this->refreshJwt === null) {
            throw new AuthenticationTokenException('Refresh JWT is required to refresh a session.');
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
}
