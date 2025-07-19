<?php

declare(strict_types=1);



it('can create an assistant', function () {
    $assistantData = [
        'graph_id' => 'test-graph',
        'name' => 'Test Assistant',
        'config' => ['key' => 'value'],
    ];

    $responseData = [
        'assistant_id' => 'asst_123',
        ...$assistantData,
    ];

    $this->mockResponse(201, $responseData);

    $result = $this->client->assistants()->create($assistantData);

    expect($result)
        ->toBeArray()
        ->toHaveKey('assistant_id')
        ->and($result['assistant_id'])->toBe('asst_123');
});

it('can find an assistant by id', function () {
    $assistantId = 'asst_123';
    $responseData = [
        'assistant_id' => $assistantId,
        'name' => 'Test Assistant',
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->assistants()->find($assistantId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('assistant_id')
        ->and($result['assistant_id'])->toBe($assistantId);
});

it('can search assistants', function () {
    $searchParams = [
        'limit' => 10,
        'offset' => 0,
        'graph_id' => 'test-graph',
    ];

    $responseData = [
        'assistants' => [
            ['assistant_id' => 'asst_1', 'name' => 'Assistant 1'],
            ['assistant_id' => 'asst_2', 'name' => 'Assistant 2'],
        ],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->assistants()->search($searchParams);

    expect($result)
        ->toBeArray()
        ->toHaveKey('assistants')
        ->and($result['assistants'])->toHaveCount(2);
});

it('can update an assistant', function () {
    $assistantId = 'asst_123';
    $updateData = ['name' => 'Updated Assistant'];

    $responseData = [
        'assistant_id' => $assistantId,
        'name' => 'Updated Assistant',
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->assistants()->update($assistantId, $updateData);

    expect($result)
        ->toBeArray()
        ->toHaveKey('name')
        ->and($result['name'])->toBe('Updated Assistant');
});

it('can delete an assistant', function () {
    $assistantId = 'asst_123';

    $this->mockResponse(204);

    $result = $this->client->assistants()->delete($assistantId);

    expect($result)->toBeArray();
});

it('can get assistant versions', function () {
    $assistantId = 'asst_123';
    $responseData = [
        'versions' => [
            ['version' => 1, 'created_at' => '2024-01-01T00:00:00Z'],
            ['version' => 2, 'created_at' => '2024-01-02T00:00:00Z'],
        ],
    ];

    $this->mockResponse(200, $responseData);

    $result = $this->client->assistants()->versions($assistantId);

    expect($result)
        ->toBeArray()
        ->toHaveKey('versions')
        ->and($result['versions'])->toHaveCount(2);
});
