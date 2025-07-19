<?php

declare(strict_types=1);

namespace LangGraphPlatform\Resources;

use LangGraphPlatform\Http\Client;

/**
 * Client for managing threads in LangGraph Platform.
 */
class ThreadsClient
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Create a new thread.
     */
    public function create(array $params = []): array
    {
        return $this->httpClient->post('threads', $params);
    }

    /**
     * Get a thread by ID.
     */
    public function get(string $threadId): array
    {
        return $this->httpClient->get("threads/{$threadId}");
    }

    /**
     * Update a thread.
     */
    public function update(string $threadId, array $params): array
    {
        return $this->httpClient->patch("threads/{$threadId}", $params);
    }

    /**
     * Delete a thread.
     */
    public function delete(string $threadId): ?array
    {
        return $this->httpClient->delete("threads/{$threadId}");
    }

    /**
     * Search for threads with optional filters.
     */
    public function search(array $params = []): array
    {
        return $this->httpClient->post('threads/search', $params);
    }

    /**
     * Copy a thread.
     */
    public function copy(string $threadId, array $params = []): array
    {
        return $this->httpClient->post("threads/{$threadId}/copy", $params);
    }

    /**
     * Get the current state of a thread.
     */
    public function state(string $threadId): array
    {
        return $this->httpClient->get("threads/{$threadId}/state");
    }

    /**
     * Get the state of a thread at a specific checkpoint.
     */
    public function stateAtCheckpoint(string $threadId, string $checkpointId): array
    {
        return $this->httpClient->get("threads/{$threadId}/state/{$checkpointId}");
    }

    /**
     * Update the state of a thread.
     */
    public function updateState(string $threadId, array $params): array
    {
        return $this->httpClient->post("threads/{$threadId}/state", $params);
    }

    /**
     * Add state to a thread.
     */
    public function addState(string $threadId, array $params): array
    {
        return $this->httpClient->patch("threads/{$threadId}/state", $params);
    }

    /**
     * Get the history of a thread.
     */
    public function history(string $threadId, array $params = []): array
    {
        return $this->httpClient->get("threads/{$threadId}/history", $params);
    }

    /**
     * Get thread messages.
     */
    public function messages(string $threadId, array $params = []): array
    {
        return $this->httpClient->get("threads/{$threadId}/messages", $params);
    }
}
