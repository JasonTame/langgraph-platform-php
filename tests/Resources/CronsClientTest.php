<?php

declare(strict_types=1);

it('can create a cron job', function () {
    $cronData = [
        'schedule' => '0 9 * * *',
        'assistant_id' => 'asst_123',
        'input' => ['message' => 'Daily task'],
    ];

    $responseData = [
        'cron_id' => 'cron_123',
        'enabled' => true,
        ...$cronData,
    ];

    $this->mockResponse(201, $responseData);

    $result = $this->client->crons()->create($cronData);

    expect($result)
        ->toBeArray()
        ->toHaveKey('cron_id')
        ->and($result['cron_id'])->toBe('cron_123');
});

it('can create a cron job for a thread', function () {
    $threadId = 'thread_123';
    $cronData = [
        'schedule' => '0 9 * * *',
        'assistant_id' => 'asst_123',
    ];

    $responseData = [
        'cron_id' => 'cron_123',
        'thread_id' => $threadId,
        ...$cronData,
    ];

    $this->mockResponse(201, $responseData);

    $result = $this->client->crons()->createForThread($threadId, $cronData);

    expect($result)
        ->toBeArray()
        ->toHaveKey('cron_id')
        ->and($result['thread_id'])->toBe($threadId);
});

it('can get a cron job', function () {
    $cronId = 'cron_123';
    $responseData = [
        'cron_id' => $cronId,
        'schedule' => '0 9 * * *',
        'enabled' => true,
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->crons()->get($cronId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('cron_id')
        ->and($result['cron_id'])->toBe($cronId);
});

it('can list cron jobs', function () {
    $responseData = [
        'crons' => [
            ['cron_id' => 'cron_1', 'enabled' => true],
            ['cron_id' => 'cron_2', 'enabled' => false],
        ],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->crons()->list();

    expect($result)
        ->toBeArray()
        ->toHaveKey('crons')
        ->and($result['crons'])->toHaveCount(2);
});

it('can update a cron job', function () {
    $cronId = 'cron_123';
    $updateData = ['enabled' => false];

    $responseData = [
        'cron_id' => $cronId,
        'enabled' => false,
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->crons()->update($cronId, $updateData);

    expect($result)
        ->toBeArray()
        ->toHaveKey('enabled')
        ->and($result['enabled'])->toBe(false);
});

it('can delete a cron job', function () {
    $cronId = 'cron_123';

    $this->mockResponse(204);

    $result = $this->client->crons()->delete($cronId);

    expect($result)->toBeArray();
});

it('can enable a cron job', function () {
    $cronId = 'cron_123';

    $responseData = [
        'cron_id' => $cronId,
        'enabled' => true,
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->crons()->enable($cronId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('enabled')
        ->and($result['enabled'])->toBe(true);
});

it('can disable a cron job', function () {
    $cronId = 'cron_123';

    $responseData = [
        'cron_id' => $cronId,
        'enabled' => false,
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->crons()->disable($cronId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('enabled')
        ->and($result['enabled'])->toBe(false);
});
