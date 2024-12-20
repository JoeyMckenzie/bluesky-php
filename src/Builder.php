<?php

declare(strict_types=1);

namespace Bluesky;

use Bluesky\Enums\TargetApi;
use Bluesky\Http\Connector;
use Bluesky\ValueObjects\Connector\BaseUri;
use Bluesky\ValueObjects\Connector\Headers;
use Bluesky\ValueObjects\Connector\QueryParams;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;

/**
 * Client builder/factory for configuring the API connector to the Bluesky API.
 */
final class Builder
{
    /**
     * The HTTP client for the requests.
     */
    private ?ClientInterface $httpClient = null;

    /**
     * The HTTP headers for the requests.
     *
     * @var array<string, string>
     */
    private array $headers = [];

    /**
     * The query parameters to be included on each outgoing request.
     *
     * @var array<string, string|int>
     */
    private array $queryParams = [];

    /**
     * Username associated to client authenticated requests.
     */
    private ?string $username = null;

    /**
     * The endpoint to send calls to. Bluesky offers a public API as well, but we default to the authenticated API.
     */
    private string $baseUri = Client::API_BASE_URL;

    /**
     * Sets the HTTP client for the requests. If no client is provided the
     * factory will try to find one using PSR-18 HTTP Client Discovery.
     */
    public function withHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Adds a custom header to each outgoing request.
     */
    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Adds a custom query parameter to the request url for each outgoing request.
     */
    public function withQueryParam(string $name, string $value): self
    {
        $this->queryParams[$name] = $value;

        return $this;
    }

    /**
     * The username associated to the client instance.
     */
    public function withUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * The API to send calls, either public or authenticated.
     */
    public function withTargetApi(TargetApi $targetApi): self
    {
        $this->baseUri = match ($targetApi) {
            TargetApi::PUBLIC => Client::PUBLIC_API_BASE_URL,
            TargetApi::AUTHENTICATED => Client::API_BASE_URL,
        };

        return $this;
    }

    /**
     * Creates a new Bluesky client based on the provided builder options.
     */
    public function build(): Client
    {
        $headers = Headers::create();

        // For any default headers configured for the client, we'll add those to each outbound request
        foreach ($this->headers as $name => $value) {
            $headers = $headers->withCustomHeader($name, $value);
        }

        $baseUri = BaseUri::from($this->baseUri);
        $queryParams = QueryParams::create();

        // As with the headers, we'll also include any query params configured on each request
        foreach ($this->queryParams as $name => $value) {
            $queryParams = $queryParams->withParam($name, $value);
        }

        $client = $this->httpClient ??= Psr18ClientDiscovery::find();
        $connector = new Connector($client, $baseUri, $headers, $queryParams);

        return new Client($connector, $this->username ?? '');
    }

    public function getHttpClient(): ?ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array<string, string|int>
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }
}
