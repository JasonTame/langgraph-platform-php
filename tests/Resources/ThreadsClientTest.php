<?php

declare(strict_types=1);



it('can create a thread', function () {
    $threadData = [
        'metadata' => ['key' => 'value'],
    ];

    $responseData = [
        'thread_id' => 'thread_123',
        'created_at' => '2024-01-01T00:00:00Z',
        ...$threadData,
    ];

    $this->mockResponse(201, $responseData);

    $result = $this->client->threads()->create($threadData);

    expect($result)
        ->toBeArray()
        ->toHaveKey('thread_id')
        ->and($result['thread_id'])->toBe('thread_123');
});

it('can get a thread', function () {
    $threadId = 'thread_123';
    $responseData = [
        'thread_id' => $threadId,
        'created_at' => '2024-01-01T00:00:00Z',
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->threads()->get($threadId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('thread_id')
        ->and($result['thread_id'])->toBe($threadId);
});

it('can update a thread', function () {
    $threadId = 'thread_123';
    $updateData = ['metadata' => ['updated' => true]];

    $responseData = [
        'thread_id' => $threadId,
        'metadata' => ['updated' => true],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->threads()->update($threadId, $updateData);

    expect($result)
        ->toBeArray()
        ->toHaveKey('metadata')
        ->and($result['metadata']['updated'])->toBe(true);
});

it('can delete a thread', function () {
    $threadId = 'thread_123';

    $this->mockResponse(204);

    $result = $this->client->threads()->delete($threadId);

    expect($result)->toBeArray();
});

it('can get thread state', function () {
    $threadId = 'thread_123';
    $responseData = [
        'values' => ['messages' => ['Hello', 'Hi there']],
        'next' => [],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->threads()->getState($threadId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('values')
        ->and($result['values']['messages'])->toHaveCount(2);
});

it('can update thread state', function () {
    $threadId = 'thread_123';
    $stateData = ['values' => ['messages' => ['New message']]];

    $responseData = [
        'values' => ['messages' => ['New message']],
        'next' => [],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->threads()->updateState($threadId, $stateData);

    expect($result)
        ->toBeArray()
        ->toHaveKey('values');
});

it('can get thread history', function () {
    $threadId = 'thread_123';
    $responseData = [
        'values' => [
            ['checkpoint_id' => 'cp_1', 'values' => ['step' => 1]],
            ['checkpoint_id' => 'cp_2', 'values' => ['step' => 2]],
        ],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->threads()->getHistory($threadId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('values')
        ->and($result['values'])->toHaveCount(2);
});

it('can search threads', function () {
    $searchParams = [
        'limit' => 10,
        'metadata' => ['key' => 'value'],
    ];

    $responseData = [
        'threads' => [
            ['thread_id' => 'thread_1'],
            ['thread_id' => 'thread_2'],
        ],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->threads()->search($searchParams);

    expect($result)
        ->toBeArray()
        ->toHaveKey('threads')
        ->and($result['threads'])->toHaveCount(2);
});
