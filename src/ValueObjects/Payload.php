<?php

declare(strict_types=1);

namespace Bluesky\ValueObjects;

use Bluesky\Enums\HttpMethod;
use Bluesky\Enums\MediaType;
use Bluesky\ValueObjects\Connector\BaseUri;
use Bluesky\ValueObjects\Connector\Headers;
use Bluesky\ValueObjects\Connector\QueryParams;
use Http\Discovery\Psr17Factory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Payloads are sent to the server as an encapsulating HTTP request, with configurable headers, method, and parameters.
 *
 * @internal
 */
final readonly class Payload
{
    /**
     * Creates a new Request value object.
     *
     * @param  array<string, mixed>  $parameters
     */
    private function __construct(
        private MediaType $accept,
        private HttpMethod $method,
        private ResourceUri $uri,
        private array $parameters = [],
        private ?MediaType $contentType = null,
    ) {
        //
    }

    /**
     * Creates a new Payload value object from the given parameters.
     *
     * @param  array<string, mixed>  $parameters
     */
    public static function list(string $resource, array $parameters = [], ?string $suffix = null): self
    {
        $accept = MediaType::JSON;
        $method = HttpMethod::GET;
        $uri = ResourceUri::list($resource, $suffix);

        return new self($accept, $method, $uri, $parameters);
    }

    /**
     * Creates a new Payload value object from the given parameters.
     *
     * @param  array<string, mixed>  $parameters
     */
    public static function retrieve(string $resource, string $id, array $parameters = []): self
    {
        $accept = MediaType::JSON;
        $method = HttpMethod::GET;
        $uri = ResourceUri::retrieve($resource, $id);

        return new self($accept, $method, $uri, $parameters);
    }

    /**
     * Creates a new Payload value object from the given parameters.
     *
     * @param  array<string, mixed>  $parameters
     */
    public static function create(string $resource, array $parameters, ?MediaType $contentType = null): self
    {
        $accept = MediaType::JSON;
        $method = HttpMethod::POST;
        $uri = ResourceUri::create($resource);

        return new self($accept, $method, $uri, $parameters, $contentType);
    }

    /**
     * Creates a new Psr 7 Request instance based on information passed on the request payload.
     * In the case of query parameters, if the client is constructed with any parameters,
     * we'll append them to each request that is sent to the server.
     */
    public function toRequest(BaseUri $baseUri, Headers $headers, QueryParams $queryParams): RequestInterface
    {
        $psr17Factory = new Psr17Factory;
        $uri = "$baseUri$this->uri";
        $queryParams = [...$queryParams->toArray(), ...$this->parameters];

        if ($queryParams !== []) {
            $uri .= '?'.http_build_query($queryParams);
        }

        $headers = $headers->withAccept($this->accept);

        if ($this->contentType instanceof MediaType) {
            $headers = $headers->withContentType($this->contentType);
        }

        $body = $this->method === HttpMethod::POST
            ? $psr17Factory->createStream(json_encode($this->parameters, JSON_THROW_ON_ERROR))
            : null;
        $request = $psr17Factory->createRequest($this->method->value, $uri);

        if ($body instanceof StreamInterface) {
            $request = $request->withBody($body);
        }

        foreach ($headers->toArray() as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        return $request;
    }
}
