<?php

declare(strict_types=1);

namespace LangGraphPlatform\Resources;

use LangGraphPlatform\Http\Client;

/**
 * Client for managing assistants in LangGraph Platform.
 */
class AssistantsClient
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Create a new assistant.
     */
    public function create(array $params): array
    {
        return $this->httpClient->post('assistants', $params);
    }

    /**
     * Find an assistant by ID.
     */
    public function find(string $assistantId): array
    {
        return $this->httpClient->get("assistants/{$assistantId}");
    }

    /**
     * Search for assistants with optional filters.
     */
    public function search(array $params = []): array
    {
        return $this->httpClient->post('assistants/search', $params);
    }

    /**
     * List all assistants.
     */
    public function list(array $params = []): array
    {
        return $this->httpClient->get('assistants', $params);
    }

    /**
     * Update an assistant.
     */
    public function update(string $assistantId, array $params): array
    {
        return $this->httpClient->patch("assistants/{$assistantId}", $params);
    }

    /**
     * Delete an assistant.
     */
    public function delete(string $assistantId): ?array
    {
        return $this->httpClient->delete("assistants/{$assistantId}");
    }

    /**
     * Get assistant graph information.
     */
    public function graph(string $assistantId, array $params = []): array
    {
        return $this->httpClient->get("assistants/{$assistantId}/graph", $params);
    }

    /**
     * Get assistant schema information.
     */
    public function schemas(string $assistantId): array
    {
        return $this->httpClient->get("assistants/{$assistantId}/schemas");
    }

    /**
     * Get assistant versions.
     */
    public function versions(string $assistantId, array $params = []): array
    {
        return $this->httpClient->get("assistants/{$assistantId}/versions", $params);
    }

    /**
     * Set the latest version of an assistant.
     */
    public function setLatest(string $assistantId, int $version): array
    {
        return $this->httpClient->post("assistants/{$assistantId}/versions/{$version}/latest");
    }
}
