<?php

declare(strict_types=1);

use LangGraphPlatform\Exceptions\BadRequestException;
use LangGraphPlatform\Exceptions\LangGraphException;
use LangGraphPlatform\Exceptions\NotFoundException;
use LangGraphPlatform\Exceptions\UnauthorizedException;

it('can make get request', function () {
    $responseData = ['success' => true];
    $this->mockResponse(200, $responseData);

    $result = $this->client->getHttpClient()->get('test');

    expect($result)
        ->toBeArray()
        ->toHaveKey('success')
        ->and($result['success'])->toBe(true);
});

it('can make post request', function () {
    $data = ['name' => 'Test'];
    $responseData = ['id' => 123, ...$data];

    $this->mockResponse(201, $responseData);

    $result = $this->client->getHttpClient()->post('test', $data);

    expect($result)
        ->toBeArray()
        ->toHaveKey('id')
        ->and($result['name'])->toBe('Test');
});

it('can make put request', function () {
    $data = ['name' => 'Updated'];
    $responseData = ['id' => 123, ...$data];

    $this->mockResponse(200, $responseData);

    $result = $this->client->getHttpClient()->put('test/123', $data);

    expect($result)
        ->toBeArray()
        ->toHaveKey('name')
        ->and($result['name'])->toBe('Updated');
});

it('can make delete request', function () {
    $this->mockResponse(204);

    $result = $this->client->getHttpClient()->delete('test/123');

    expect($result)->toBeArray();
});

it('throws unauthorized exception for 401 errors', function () {
    $this->mockErrorResponse(401, 'Unauthorized');

    expect(fn() => $this->client->getHttpClient()->get('test'))
        ->toThrow(UnauthorizedException::class);
});

it('throws bad request exception for 400 errors', function () {
    $this->mockErrorResponse(400, 'Bad Request');

    expect(fn() => $this->client->getHttpClient()->get('test'))
        ->toThrow(BadRequestException::class);
});

it('throws not found exception for 404 errors', function () {
    $this->mockErrorResponse(404, 'Not Found');

    expect(fn() => $this->client->getHttpClient()->get('test'))
        ->toThrow(NotFoundException::class);
});

it('throws generic exception for other errors', function () {
    $this->mockErrorResponse(500, 'Internal Server Error');

    expect(fn() => $this->client->getHttpClient()->get('test'))
        ->toThrow(LangGraphException::class);
});

it('includes response data in exceptions', function () {
    $errorData = ['error' => 'Test error', 'code' => 'TEST_ERROR'];
    $this->mockResponse(400, $errorData);

    try {
        $this->client->getHttpClient()->get('test');
        $this->fail('Expected exception was not thrown');
    } catch (LangGraphException $e) {
        expect($e->getResponseData())
            ->toBeArray()
            ->toHaveKey('error')
            ->and($e->getResponseData()['error'])->toBe('Test error');
    }
});

it('can handle empty responses', function () {
    $this->mockResponse(204);

    $result = $this->client->getHttpClient()->get('test');

    expect($result)->toBeArray()->toBeEmpty();
});

it('can handle streaming responses', function () {
    $this->mockResponse(200, [], ['Content-Type' => 'text/event-stream']);

    $events = [];
    $callback = function ($event) use (&$events) {
        $events[] = $event;
    };

    // This will test the stream method exists and is callable
    expect(fn() => $this->client->getHttpClient()->stream('GET', 'test', [], $callback))
        ->not->toThrow(Exception::class);
});
