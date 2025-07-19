<?php

declare(strict_types=1);

use JasonTame\LangGraphClient\LangGraphClient;
use JasonTame\LangGraphClient\Resources\AssistantsClient;
use JasonTame\LangGraphClient\Resources\CronsClient;
use JasonTame\LangGraphClient\Resources\RunsClient;
use JasonTame\LangGraphClient\Resources\StoreClient;
use JasonTame\LangGraphClient\Resources\ThreadsClient;

it('can be instantiated with config', function () {
    $config = [
        'api_key' => 'test-key',
        'base_url' => 'https://test.com',
    ];

    $client = new LangGraphClient($config);

    expect($client)->toBeInstanceOf(LangGraphClient::class);
});

it('can be instantiated without config', function () {
    $client = new LangGraphClient;

    expect($client)->toBeInstanceOf(LangGraphClient::class);
});

it('can create client from environment', function () {
    putenv('LANGGRAPH_API_KEY=env-test-key');
    putenv('LANGGRAPH_BASE_URL=https://env.test.com');

    $client = LangGraphClient::fromEnvironment();

    expect($client)->toBeInstanceOf(LangGraphClient::class);
});

it('provides access to resource clients', function () {
    $client = new LangGraphClient(['api_key' => 'test']);

    expect($client->assistants())->toBeInstanceOf(AssistantsClient::class);
    expect($client->threads())->toBeInstanceOf(ThreadsClient::class);
    expect($client->runs())->toBeInstanceOf(RunsClient::class);
    expect($client->crons())->toBeInstanceOf(CronsClient::class);
    expect($client->store())->toBeInstanceOf(StoreClient::class);
});

it('can be configured after instantiation', function () {
    $client = new LangGraphClient;

    $newConfig = ['api_key' => 'new-key'];
    $configuredClient = $client->configure($newConfig);

    expect($configuredClient)->toBeInstanceOf(LangGraphClient::class);
});

it('has http client accessor', function () {
    $client = new LangGraphClient(['api_key' => 'test']);

    expect($client->getHttpClient())->toBeInstanceOf(\JasonTame\LangGraphClient\Http\Client::class);
});
