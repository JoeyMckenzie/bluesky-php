<?php

declare(strict_types=1);

namespace Bluesky;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\ActorContract;
use Bluesky\Contracts\Resources\FeedContract;
use Bluesky\Contracts\Resources\SessionContract;
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

    public ?string $accessJwt = null;

    public ?string $refreshJwt = null;

    /**
     * Creates a client instance with the provided client transport abstraction.
     */
    public function __construct(
        private readonly ConnectorContract $connector,
        public readonly string $username
    ) {
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

    public function actor(): ActorContract
    {
        return new Actor($this->connector, $this->accessJwt);
    }

    public function feed(): FeedContract
    {
        return new Feed($this->connector, $this->username, $this->accessJwt);
    }
}
