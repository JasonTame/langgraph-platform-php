<?php

declare(strict_types=1);

namespace JasonTame\LangGraphClient\Resources;

use JasonTame\LangGraphClient\Http\Client;

/**
 * Client for managing runs in LangGraph Platform.
 */
class RunsClient
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Create a new run on a thread.
     */
    public function create(string $threadId, array $params): array
    {
        return $this->httpClient->post("threads/{$threadId}/runs", $params);
    }

    /**
     * Create a stateless run (without a thread).
     */
    public function createStateless(array $params): array
    {
        return $this->httpClient->post('runs', $params);
    }

    /**
     * Get a run by ID.
     */
    public function get(string $threadId, string $runId): array
    {
        return $this->httpClient->get("threads/{$threadId}/runs/{$runId}");
    }

    /**
     * Update a run.
     */
    public function update(string $threadId, string $runId, array $params): array
    {
        return $this->httpClient->patch("threads/{$threadId}/runs/{$runId}", $params);
    }

    /**
     * Cancel a run.
     */
    public function cancel(string $threadId, string $runId): array
    {
        return $this->httpClient->post("threads/{$threadId}/runs/{$runId}/cancel");
    }

    /**
     * Delete a run.
     */
    public function delete(string $threadId, string $runId): ?array
    {
        return $this->httpClient->delete("threads/{$threadId}/runs/{$runId}");
    }

    /**
     * List runs for a thread.
     */
    public function list(string $threadId, array $params = []): array
    {
        return $this->httpClient->get("threads/{$threadId}/runs", $params);
    }

    /**
     * Search for runs.
     */
    public function search(array $params = []): array
    {
        return $this->httpClient->post('runs/search', $params);
    }

    /**
     * Stream a run with real-time updates.
     */
    public function stream(string $threadId, array $params, ?callable $callback = null): \Generator
    {
        $streamParams = array_merge($params, ['stream' => true]);

        $stream = $this->httpClient->stream("threads/{$threadId}/runs", 'POST', $streamParams);

        foreach ($stream as $event) {
            if ($callback !== null) {
                $callback($event);
            }
            yield $event;
        }
    }

    /**
     * Stream a stateless run.
     */
    public function streamStateless(array $params, ?callable $callback = null): \Generator
    {
        $streamParams = array_merge($params, ['stream' => true]);

        $stream = $this->httpClient->stream('runs', 'POST', $streamParams);

        foreach ($stream as $event) {
            if ($callback !== null) {
                $callback($event);
            }
            yield $event;
        }
    }

    /**
     * Create a run and wait for completion.
     */
    public function wait(string $threadId, array $params, int $pollInterval = 1): array
    {
        $run = $this->create($threadId, $params);
        $runId = $run['run_id'];

        while (true) {
            $currentRun = $this->get($threadId, $runId);

            if (in_array($currentRun['status'], ['success', 'error', 'cancelled', 'failed'])) {
                return $currentRun;
            }

            sleep($pollInterval);
        }
    }

    /**
     * Create a stateless run and wait for completion.
     */
    public function waitStateless(array $params, int $pollInterval = 1): array
    {
        $run = $this->createStateless($params);
        $runId = $run['run_id'];

        while (true) {
            // For stateless runs, we need to check via a different endpoint
            $currentRun = $this->getStateless($runId);

            if (in_array($currentRun['status'], ['success', 'error', 'cancelled', 'failed'])) {
                return $currentRun;
            }

            sleep($pollInterval);
        }
    }

    /**
     * Get a stateless run by ID.
     */
    public function getStateless(string $runId): array
    {
        return $this->httpClient->get("runs/{$runId}");
    }

    /**
     * Join a run (wait for it to complete).
     */
    public function join(string $threadId, string $runId): array
    {
        return $this->httpClient->get("threads/{$threadId}/runs/{$runId}/join");
    }

    /**
     * Stream and join a run.
     */
    public function joinStream(string $threadId, string $runId, ?callable $callback = null): \Generator
    {
        $stream = $this->httpClient->stream("threads/{$threadId}/runs/{$runId}/stream", 'GET');

        foreach ($stream as $event) {
            if ($callback !== null) {
                $callback($event);
            }
            yield $event;
        }
    }
}
