<?php

declare(strict_types=1);

namespace LangGraphPlatform\Resources;

use LangGraphPlatform\Http\Client;

/**
 * Client for managing cron jobs in LangGraph Platform.
 */
class CronsClient
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Create a new cron job.
     */
    public function create(array $params): array
    {
        return $this->httpClient->post('crons', $params);
    }

    /**
     * Create a cron job for a specific thread.
     */
    public function createForThread(string $threadId, array $params): array
    {
        return $this->httpClient->post("threads/{$threadId}/crons", $params);
    }

    /**
     * Get a cron job by ID.
     */
    public function get(string $cronId): array
    {
        return $this->httpClient->get("crons/{$cronId}");
    }

    /**
     * Update a cron job.
     */
    public function update(string $cronId, array $params): array
    {
        return $this->httpClient->patch("crons/{$cronId}", $params);
    }

    /**
     * Delete a cron job.
     */
    public function delete(string $cronId): ?array
    {
        return $this->httpClient->delete("crons/{$cronId}");
    }

    /**
     * List all cron jobs.
     */
    public function list(array $params = []): array
    {
        return $this->httpClient->get('crons', $params);
    }

    /**
     * Search for cron jobs.
     */
    public function search(array $params = []): array
    {
        return $this->httpClient->post('crons/search', $params);
    }

    /**
     * Enable a cron job.
     */
    public function enable(string $cronId): array
    {
        return $this->httpClient->post("crons/{$cronId}/enable");
    }

    /**
     * Disable a cron job.
     */
    public function disable(string $cronId): array
    {
        return $this->httpClient->post("crons/{$cronId}/disable");
    }

    /**
     * Trigger a cron job manually.
     */
    public function trigger(string $cronId): array
    {
        return $this->httpClient->post("crons/{$cronId}/trigger");
    }

    /**
     * Get cron job execution history.
     */
    public function history(string $cronId, array $params = []): array
    {
        return $this->httpClient->get("crons/{$cronId}/history", $params);
    }

    /**
     * Get the next scheduled run time for a cron job.
     */
    public function nextRun(string $cronId): array
    {
        return $this->httpClient->get("crons/{$cronId}/next-run");
    }
}
