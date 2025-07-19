<?php

declare(strict_types=1);

use LangGraphPlatform\Facades\LangGraphPlatform;
use LangGraphPlatform\LangGraphPlatform as LangGraphPlatformClient;
use LangGraphPlatform\LangGraphPlatformServiceProvider;

it('registers the service provider correctly', function () {
    $providers = $this->app->getLoadedProviders();

    expect($providers)->toHaveKey(LangGraphPlatformServiceProvider::class);
});

it('binds the main client in the container', function () {
    $client = $this->app->make(LangGraphPlatformClient::class);

    expect($client)->toBeInstanceOf(LangGraphPlatformClient::class);
});

it('resolves the same instance as singleton', function () {
    $client1 = $this->app->make(LangGraphPlatformClient::class);
    $client2 = $this->app->make(LangGraphPlatformClient::class);

    expect($client1)->toBe($client2);
});

it('can use facade to access client', function () {
    config(['langgraph-platform-php.api_key' => 'test-key']);

    $client = LangGraphPlatform::fromEnvironment();

    expect($client)->toBeInstanceOf(LangGraphPlatformClient::class);
});

it('publishes config file', function () {
    expect(config('langgraph-platform-php'))->toBeArray();
});

it('has correct configuration structure', function () {
    $config = config('langgraph-platform-php');

    expect($config)
        ->toBeArray()
        ->toHaveKeys(['api_key', 'base_url', 'timeout', 'connect_timeout', 'retries']);
});
