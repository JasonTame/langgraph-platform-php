<?php

declare(strict_types=1);

use LangGraphPlatform\LangGraphPlatform;
use LangGraphPlatform\Resources\AssistantsClient;
use LangGraphPlatform\Resources\CronsClient;
use LangGraphPlatform\Resources\RunsClient;
use LangGraphPlatform\Resources\StoreClient;
use LangGraphPlatform\Resources\ThreadsClient;

it('can be instantiated with config', function () {
    $config = [
        'api_key' => 'test-key',
        'base_url' => 'https://test.com',
    ];

    $client = new LangGraphPlatform($config);

    expect($client)->toBeInstanceOf(LangGraphPlatform::class);
});

it('can be instantiated without config', function () {
    $client = new LangGraphPlatform;

    expect($client)->toBeInstanceOf(LangGraphPlatform::class);
});

it('can create client from environment', function () {
    putenv('LANGGRAPH_API_KEY=env-test-key');
    putenv('LANGGRAPH_BASE_URL=https://env.test.com');

    $client = LangGraphPlatform::fromEnvironment();

    expect($client)->toBeInstanceOf(LangGraphPlatform::class);
});

it('provides access to resource clients', function () {
    $client = new LangGraphPlatform(['api_key' => 'test']);

    expect($client->assistants())->toBeInstanceOf(AssistantsClient::class);
    expect($client->threads())->toBeInstanceOf(ThreadsClient::class);
    expect($client->runs())->toBeInstanceOf(RunsClient::class);
    expect($client->crons())->toBeInstanceOf(CronsClient::class);
    expect($client->store())->toBeInstanceOf(StoreClient::class);
});

it('can be configured after instantiation', function () {
    $client = new LangGraphPlatform;

    $newConfig = ['api_key' => 'new-key'];
    $configuredClient = $client->configure($newConfig);

    expect($configuredClient)->toBeInstanceOf(LangGraphPlatform::class);
});

it('has http client accessor', function () {
    $client = new LangGraphPlatform(['api_key' => 'test']);

    expect($client->getHttpClient())->toBeInstanceOf(\LangGraphPlatform\Http\Client::class);
});
