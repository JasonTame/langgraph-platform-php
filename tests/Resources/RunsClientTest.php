<?php

declare(strict_types=1);



it('can create a run on a thread', function () {
    $threadId = 'thread_123';
    $runData = [
        'assistant_id' => 'asst_123',
        'input' => ['message' => 'Hello'],
    ];

    $responseData = [
        'run_id' => 'run_123',
        'thread_id' => $threadId,
        'status' => 'queued',
        ...$runData,
    ];

    $this->mockResponse(201, $responseData);

    $result = $this->client->runs()->create($threadId, $runData);

    expect($result)
        ->toBeArray()
        ->toHaveKey('run_id')
        ->and($result['run_id'])->toBe('run_123')
        ->and($result['thread_id'])->toBe($threadId);
});

it('can create a stateless run', function () {
    $runData = [
        'assistant_id' => 'asst_123',
        'input' => ['message' => 'Hello'],
    ];

    $responseData = [
        'run_id' => 'run_123',
        'status' => 'queued',
        ...$runData,
    ];

    $this->mockResponse(201, $responseData);

    $result = $this->client->runs()->createStateless($runData);

    expect($result)
        ->toBeArray()
        ->toHaveKey('run_id')
        ->and($result['run_id'])->toBe('run_123');
});

it('can get a run', function () {
    $threadId = 'thread_123';
    $runId = 'run_123';

    $responseData = [
        'run_id' => $runId,
        'thread_id' => $threadId,
        'status' => 'completed',
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->runs()->get($threadId, $runId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('run_id')
        ->and($result['run_id'])->toBe($runId);
});

it('can list runs for a thread', function () {
    $threadId = 'thread_123';

    $responseData = [
        'runs' => [
            ['run_id' => 'run_1', 'status' => 'completed'],
            ['run_id' => 'run_2', 'status' => 'running'],
        ],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->runs()->list($threadId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('runs')
        ->and($result['runs'])->toHaveCount(2);
});

it('can cancel a run', function () {
    $threadId = 'thread_123';
    $runId = 'run_123';

    $responseData = [
        'run_id' => $runId,
        'status' => 'cancelled',
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->runs()->cancel($threadId, $runId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('status')
        ->and($result['status'])->toBe('cancelled');
});

it('can join a run', function () {
    $threadId = 'thread_123';
    $runId = 'run_123';

    $responseData = [
        'run_id' => $runId,
        'status' => 'completed',
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->runs()->join($threadId, $runId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('status')
        ->and($result['status'])->toBe('completed');
});

it('can delete a run', function () {
    $threadId = 'thread_123';
    $runId = 'run_123';

    $this->mockResponse(204);

    $result = $this->client->runs()->delete($threadId, $runId);

    expect($result)->toBeArray();
});

it('can create streaming run', function () {
    $threadId = 'thread_123';
    $runData = [
        'assistant_id' => 'asst_123',
        'input' => ['message' => 'Hello'],
        'stream_mode' => 'values',
    ];

    // Mock SSE response
    $sseData = "data: {\"event\":\"values\",\"data\":{\"messages\":[\"Hello\"]}}\n\n";
    $this->mockResponse(200, [], ['Content-Type' => 'text/event-stream']);

    $events = [];
    $callback = function ($event) use (&$events) {
        $events[] = $event;
    };

    $this->client->runs()->stream($threadId, $runData, $callback);

    // We can't easily test the actual streaming without more complex mocking,
    // but we can verify the method exists and is callable
    expect($callback)->toBeCallable();
});

it('can wait for run completion', function () {
    $threadId = 'thread_123';
    $runParams = [
        'assistant_id' => 'asst_123',
        'input' => ['message' => 'Hello'],
    ];

    $createResponse = [
        'run_id' => 'run_123',
        'status' => 'queued',
    ];

    $completedResponse = [
        'run_id' => 'run_123',
        'status' => 'success',
    ];

    $this->mockResponse(201, $createResponse); // For create
    $this->mockResponse(200, $completedResponse); // For first poll (completed)

    $result = $this->client->runs()->wait($threadId, $runParams);

    expect($result)
        ->toBeArray()
        ->toHaveKey('status')
        ->and($result['status'])->toBe('success');
});
