<?php

declare(strict_types=1);

namespace LangGraphPlatform\Resources;

use LangGraphPlatform\Http\Client;

/**
 * Client for managing the key-value store in LangGraph Platform.
 */
class StoreClient
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Store a key-value pair.
     */
    public function put(string $namespace, string $key, $value): array
    {
        return $this->httpClient->put("store/{$namespace}/{$key}", [
            'value' => $value,
        ]);
    }

    /**
     * Get a value by key.
     */
    public function get(string $namespace, string $key): array
    {
        return $this->httpClient->get("store/{$namespace}/{$key}");
    }

    /**
     * Delete a key-value pair.
     */
    public function delete(string $namespace, string $key): ?array
    {
        return $this->httpClient->delete("store/{$namespace}/{$key}");
    }

    /**
     * List items in a namespace.
     */
    public function list(string $namespace, array $params = []): array
    {
        return $this->httpClient->get("store/{$namespace}", $params);
    }

    /**
     * Search for items in the store.
     */
    public function search(array $params = []): array
    {
        return $this->httpClient->post('store/search', $params);
    }

    /**
     * Batch put multiple items.
     */
    public function batchPut(array $items): array
    {
        return $this->httpClient->post('store/batch', [
            'items' => $items,
        ]);
    }

    /**
     * Batch get multiple items.
     */
    public function batchGet(array $keys): array
    {
        return $this->httpClient->post('store/batch/get', [
            'keys' => $keys,
        ]);
    }

    /**
     * Batch delete multiple items.
     */
    public function batchDelete(array $keys): array
    {
        return $this->httpClient->post('store/batch/delete', [
            'keys' => $keys,
        ]);
    }

    /**
     * List all namespaces.
     */
    public function listNamespaces(array $params = []): array
    {
        return $this->httpClient->get('store/namespaces', $params);
    }

    /**
     * Create a new namespace.
     */
    public function createNamespace(string $namespace, array $params = []): array
    {
        return $this->httpClient->post("store/namespaces/{$namespace}", $params);
    }

    /**
     * Delete a namespace and all its contents.
     */
    public function deleteNamespace(string $namespace): ?array
    {
        return $this->httpClient->delete("store/namespaces/{$namespace}");
    }

    /**
     * Get namespace information.
     */
    public function getNamespace(string $namespace): array
    {
        return $this->httpClient->get("store/namespaces/{$namespace}");
    }

    /**
     * Update namespace metadata.
     */
    public function updateNamespace(string $namespace, array $params): array
    {
        return $this->httpClient->patch("store/namespaces/{$namespace}", $params);
    }

    /**
     * Check if a key exists.
     */
    public function exists(string $namespace, string $key): bool
    {
        try {
            $this->get($namespace, $key);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get multiple items by keys in a single namespace.
     */
    public function getMultiple(string $namespace, array $keys): array
    {
        $items = [];
        foreach ($keys as $key) {
            try {
                $items[$key] = $this->get($namespace, $key);
            } catch (\Exception $e) {
                $items[$key] = null;
            }
        }

        return $items;
    }

    /**
     * Set multiple items in a single namespace.
     */
    public function putMultiple(string $namespace, array $items): array
    {
        $results = [];
        foreach ($items as $key => $value) {
            $results[$key] = $this->put($namespace, $key, $value);
        }

        return $results;
    }
}
