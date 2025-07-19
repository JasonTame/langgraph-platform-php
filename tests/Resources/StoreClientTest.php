<?php

declare(strict_types=1);



it('can put a value in store', function () {
    $namespace = 'test-namespace';
    $key = 'test-key';
    $value = ['message' => 'Hello World'];

    $responseData = [
        'namespace' => $namespace,
        'key' => $key,
        'value' => $value,
        'created_at' => '2024-01-01T00:00:00Z',
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->store()->put($namespace, $key, $value);

    expect($result)
        ->toBeArray()
        ->toHaveKey('key')
        ->and($result['key'])->toBe($key)
        ->and($result['namespace'])->toBe($namespace);
});

it('can get a value from store', function () {
    $namespace = 'test-namespace';
    $key = 'test-key';

    $responseData = [
        'namespace' => $namespace,
        'key' => $key,
        'value' => ['message' => 'Hello World'],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->store()->get($namespace, $key);

    expect($result)
        ->toBeArray()
        ->toHaveKey('value')
        ->and($result['key'])->toBe($key);
});

it('can delete a value from store', function () {
    $namespace = 'test-namespace';
    $key = 'test-key';

    $this->mockResponse(204);

    $result = $this->client->store()->delete($namespace, $key);

    expect($result)->toBeArray();
});

it('can list values in a namespace', function () {
    $namespace = 'test-namespace';

    $responseData = [
        'items' => [
            ['key' => 'key1', 'value' => 'value1'],
            ['key' => 'key2', 'value' => 'value2'],
        ],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->store()->list($namespace);

    expect($result)
        ->toBeArray()
        ->toHaveKey('items')
        ->and($result['items'])->toHaveCount(2);
});

it('can search values in store', function () {
    $searchParams = [
        'namespace_prefix' => 'test-',
        'limit' => 10,
    ];

    $responseData = [
        'items' => [
            ['namespace' => 'test-1', 'key' => 'key1', 'value' => 'value1'],
            ['namespace' => 'test-2', 'key' => 'key2', 'value' => 'value2'],
        ],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->store()->search($searchParams);

    expect($result)
        ->toBeArray()
        ->toHaveKey('items')
        ->and($result['items'])->toHaveCount(2);
});

it('can perform batch operations', function () {
    $operations = [
        ['operation' => 'put', 'namespace' => 'ns1', 'key' => 'key1', 'value' => 'value1'],
        ['operation' => 'delete', 'namespace' => 'ns1', 'key' => 'key2'],
    ];

    $responseData = [
        'results' => [
            ['success' => true],
            ['success' => true],
        ],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->store()->batch($operations);

    expect($result)
        ->toBeArray()
        ->toHaveKey('results')
        ->and($result['results'])->toHaveCount(2);
});
