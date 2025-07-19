<?php

declare(strict_types=1);

namespace JasonTame\LangGraphClient;

use JasonTame\LangGraphClient\Http\Client as HttpClient;
use JasonTame\LangGraphClient\Resources\AssistantsClient;
use JasonTame\LangGraphClient\Resources\CronsClient;
use JasonTame\LangGraphClient\Resources\RunsClient;
use JasonTame\LangGraphClient\Resources\StoreClient;
use JasonTame\LangGraphClient\Resources\ThreadsClient;

/**
 * Main LangGraph Client SDK.
 */
class LangGraphClient
{
    private HttpClient $httpClient;

    private AssistantsClient $assistants;

    private ThreadsClient $threads;

    private RunsClient $runs;

    private CronsClient $crons;

    private StoreClient $store;

    public function __construct(?array $config = null)
    {
        // Get configuration from Laravel config if not provided
        if ($config === null) {
            $config = config('langgraph-client-php', []);
        }

        $this->httpClient = new HttpClient($config);
        $this->initializeResourceClients();
    }

    /**
     * Initialize all resource client instances.
     */
    private function initializeResourceClients(): void
    {
        $this->assistants = new AssistantsClient($this->httpClient);
        $this->threads = new ThreadsClient($this->httpClient);
        $this->runs = new RunsClient($this->httpClient);
        $this->crons = new CronsClient($this->httpClient);
        $this->store = new StoreClient($this->httpClient);
    }

    /**
     * Get assistants resource client.
     */
    public function assistants(): AssistantsClient
    {
        return $this->assistants;
    }

    /**
     * Get threads resource client.
     */
    public function threads(): ThreadsClient
    {
        return $this->threads;
    }

    /**
     * Get runs resource client.
     */
    public function runs(): RunsClient
    {
        return $this->runs;
    }

    /**
     * Get crons resource client.
     */
    public function crons(): CronsClient
    {
        return $this->crons;
    }

    /**
     * Get store resource client.
     */
    public function store(): StoreClient
    {
        return $this->store;
    }

    /**
     * Get the underlying HTTP client.
     */
    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    /**
     * Configure the client with new settings.
     */
    public function configure(array $config): self
    {
        $this->httpClient->configure($config);

        return $this;
    }

    /**
     * Create a new client instance with custom configuration.
     */
    public static function create(array $config = []): self
    {
        return new self($config);
    }

    /**
     * Create a client instance using environment variables.
     */
    public static function fromEnvironment(): self
    {
        return new self([
            'api_key' => env('LANGGRAPH_API_KEY', 'fake-api-key'),
            'base_url' => env('LANGGRAPH_BASE_URL', 'https://api.langchain.com'),
            'timeout' => (int) env('LANGGRAPH_TIMEOUT', 30),
            'retries' => (int) env('LANGGRAPH_RETRIES', 3),
        ]);
    }
}
