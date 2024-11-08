<?php

declare(strict_types=1);

namespace Bluesky\Contracts;

use Bluesky\Exceptions\ConnectorException;
use Bluesky\Exceptions\ErrorException;
use Bluesky\Exceptions\UnserializableResponseException;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;

/**
 * A top-level client connector that represents communication methods with the API.
 *
 * @internal
 */
interface ConnectorContract
{
    /**
     * Sends a request to the server.
     *
     * @return null|Response<array<array-key, mixed>>
     *
     * @throws ErrorException|UnserializableResponseException|ConnectorException
     */
    public function requestData(Payload $payload): ?Response;

    /**
     * Sends a request to the server with an access token.
     *
     * @return null|Response<array<array-key, mixed>>
     *
     * @throws ErrorException|UnserializableResponseException|ConnectorException
     */
    public function requestDataWithAccessToken(Payload $payload, string $accessToken): ?Response;
}
