<?php

declare(strict_types=1);

namespace JasonTame\LangGraphClient\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use JasonTame\LangGraphClient\LangGraphClient;
use JasonTame\LangGraphClient\LangGraphClientServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected MockHandler $mockHandler;

    protected LangGraphClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupMockHttpClient();
        $this->setupLangGraphClient();
    }

    protected function getPackageProviders($app)
    {
        return [
            LangGraphClientServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('langgraph-client-php.api_key', 'test-api-key');
        config()->set('langgraph-client-php.base_url', 'https://test.api.com');
    }

    protected function setupMockHttpClient(): void
    {
        $this->mockHandler = new MockHandler;
    }

    protected function setupLangGraphClient(): void
    {
        $handlerStack = HandlerStack::create($this->mockHandler);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);

        $config = [
            'api_key' => 'test-api-key',
            'base_url' => 'https://test.api.com',
            'timeout' => 30,
            'connect_timeout' => 10,
            'retries' => 3,
        ];

        $this->client = new LangGraphClient($config);

        // Inject the mocked Guzzle client into our HTTP client
        $reflection = new \ReflectionClass($this->client->getHttpClient());
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($this->client->getHttpClient(), $guzzleClient);
    }

    protected function mockResponse(int $status = 200, array $body = [], array $headers = []): void
    {
        $this->mockHandler->append(
            new Response($status, $headers, json_encode($body))
        );
    }

    protected function mockErrorResponse(int $status, string $message = 'Error'): void
    {
        $this->mockHandler->append(
            new Response($status, [], json_encode(['error' => $message]))
        );
    }
}
