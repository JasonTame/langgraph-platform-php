<?php

declare(strict_types=1);

namespace LangGraphPlatform\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use LangGraphPlatform\Exceptions\LangGraphException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * HTTP client wrapper for LangGraph Platform API communication.
 */
class Client
{
    private GuzzleClient $client;

    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'base_url' => 'https://api.langchain.com',
            'api_key' => 'fake-api-key',
            'timeout' => 30,
            'connect_timeout' => 5,
            'retries' => 3,
            'verify_ssl' => true,
            'default_headers' => [
                'User-Agent' => 'langgraph-platform-php/1.0',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ], $config);

        $this->client = $this->createClient();
    }

    /**
     * Create the Guzzle HTTP client with proper configuration.
     */
    private function createClient(): GuzzleClient
    {
        $stack = HandlerStack::create();

        // Add retry middleware
        $stack->push(Middleware::retry(
            $this->createRetryDecider(),
            $this->createRetryDelay()
        ));

        return new GuzzleClient([
            'base_uri' => rtrim($this->config['base_url'], '/') . '/',
            'timeout' => $this->config['timeout'],
            'connect_timeout' => $this->config['connect_timeout'],
            'verify' => $this->config['verify_ssl'],
            'handler' => $stack,
            'headers' => array_merge(
                $this->config['default_headers'],
                ['X-Api-Key' => $this->config['api_key']]
            ),
        ]);
    }

    /**
     * Create retry decision function.
     */
    private function createRetryDecider(): callable
    {
        return function (
            int $retries,
            Request $request,
            ?ResponseInterface $response = null,
            ?GuzzleException $exception = null
        ): bool {
            // Don't retry if we've exceeded max retries
            if ($retries >= $this->config['retries']) {
                return false;
            }

            // Retry on connection timeouts
            if ($exception instanceof ConnectException) {
                return true;
            }

            // Retry on 5xx server errors
            if ($response && $response->getStatusCode() >= 500) {
                return true;
            }

            // Retry on specific 429 (rate limit) errors
            if ($response && $response->getStatusCode() === 429) {
                return true;
            }

            return false;
        };
    }

    /**
     * Create retry delay function.
     */
    private function createRetryDelay(): callable
    {
        return function (int $numberOfRetries): int {
            // Exponential backoff: 1s, 2s, 4s, 8s, etc.
            return 1000 * (2 ** ($numberOfRetries - 1));
        };
    }

    /**
     * Make a GET request.
     */
    public function get(string $path, array $query = [], array $headers = []): array
    {
        try {
            $response = $this->client->get($path, [
                RequestOptions::QUERY => $query,
                RequestOptions::HEADERS => $headers,
            ]);

            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw $this->createException($e);
        }
    }

    /**
     * Make a POST request.
     */
    public function post(string $path, array $data = [], array $headers = []): array
    {
        try {
            $response = $this->client->post($path, [
                RequestOptions::JSON => $data,
                RequestOptions::HEADERS => $headers,
            ]);

            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw $this->createException($e);
        }
    }

    /**
     * Make a PUT request.
     */
    public function put(string $path, array $data = [], array $headers = []): array
    {
        try {
            $response = $this->client->put($path, [
                RequestOptions::JSON => $data,
                RequestOptions::HEADERS => $headers,
            ]);

            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw $this->createException($e);
        }
    }

    /**
     * Make a PATCH request.
     */
    public function patch(string $path, array $data = [], array $headers = []): array
    {
        try {
            $response = $this->client->patch($path, [
                RequestOptions::JSON => $data,
                RequestOptions::HEADERS => $headers,
            ]);

            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw $this->createException($e);
        }
    }

    /**
     * Make a DELETE request.
     */
    public function delete(string $path, array $headers = []): ?array
    {
        try {
            $response = $this->client->delete($path, [
                RequestOptions::HEADERS => $headers,
            ]);

            // DELETE requests might return empty responses
            if ($response->getStatusCode() === 204) {
                return null;
            }

            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw $this->createException($e);
        }
    }

    /**
     * Stream a response using Server-Sent Events.
     */
    public function stream(string $path, string $method = 'GET', array $data = [], array $headers = []): \Generator
    {
        try {
            $options = [
                RequestOptions::HEADERS => array_merge($headers, [
                    'Accept' => 'text/event-stream',
                    'Cache-Control' => 'no-cache',
                ]),
                RequestOptions::STREAM => true,
            ];

            if ($method === 'POST' && ! empty($data)) {
                $options[RequestOptions::JSON] = $data;
            }

            $response = $this->client->request($method, $path, $options);
            $body = $response->getBody();

            yield from $this->parseEventStream($body);
        } catch (GuzzleException $e) {
            throw $this->createException($e);
        }
    }

    /**
     * Parse Server-Sent Events stream.
     */
    private function parseEventStream(StreamInterface $body): \Generator
    {
        $buffer = '';

        while (! $body->eof()) {
            $chunk = $body->read(1024);
            if ($chunk === '') {
                continue;
            }

            $buffer .= $chunk;
            $lines = explode("\n", $buffer);

            // Keep the last potentially incomplete line in buffer
            $buffer = array_pop($lines);

            foreach ($lines as $line) {
                $event = $this->parseEventStreamLine($line);
                if ($event !== null) {
                    yield $event;
                }
            }
        }

        // Process any remaining buffer
        if (! empty($buffer)) {
            $event = $this->parseEventStreamLine($buffer);
            if ($event !== null) {
                yield $event;
            }
        }
    }

    /**
     * Parse a single line from an event stream.
     */
    private function parseEventStreamLine(string $line): ?array
    {
        $line = trim($line);

        if (empty($line)) {
            return null;
        }

        // Handle different SSE line types
        if (str_starts_with($line, 'data: ')) {
            $data = substr($line, 6);

            // Handle JSON data
            if ($data === '[DONE]') {
                return ['type' => 'done', 'data' => null];
            }

            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return ['type' => 'data', 'data' => $decoded];
            }

            return ['type' => 'data', 'data' => $data];
        }

        if (str_starts_with($line, 'event: ')) {
            return ['type' => 'event', 'data' => substr($line, 7)];
        }

        if (str_starts_with($line, 'id: ')) {
            return ['type' => 'id', 'data' => substr($line, 4)];
        }

        return null;
    }

    /**
     * Parse HTTP response to array.
     */
    private function parseResponse(ResponseInterface $response): array
    {
        $content = $response->getBody()->getContents();

        if (empty($content)) {
            return [];
        }

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new LangGraphException(
                'Failed to decode JSON response: ' . json_last_error_msg(),
                $response->getStatusCode(),
                null,
                $response
            );
        }

        return $data;
    }

    /**
     * Create appropriate exception from Guzzle exception.
     */
    private function createException(GuzzleException $e): LangGraphException
    {
        if ($e instanceof ClientException || $e instanceof GuzzleServerException) {
            return LangGraphException::fromResponse($e->getResponse(), $e);
        }

        return new LangGraphException(
            $e->getMessage(),
            $e->getCode(),
            $e instanceof \Exception ? $e : null
        );
    }

    /**
     * Get the underlying Guzzle client.
     */
    public function getGuzzleClient(): GuzzleClient
    {
        return $this->client;
    }

    /**
     * Get client configuration.
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Update client configuration.
     */
    public function configure(array $config): self
    {
        $this->config = array_merge($this->config, $config);
        $this->client = $this->createClient();

        return $this;
    }
}
