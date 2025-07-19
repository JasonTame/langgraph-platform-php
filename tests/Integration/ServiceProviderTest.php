<?php

declare(strict_types=1);

use JasonTame\LangGraphClient\Facades\LangGraphClient;
use JasonTame\LangGraphClient\LangGraphClient as LangGraphClientClass;
use JasonTame\LangGraphClient\LangGraphClientServiceProvider;

it('registers the service provider correctly', function () {
    $providers = $this->app->getLoadedProviders();

    expect($providers)->toHaveKey(LangGraphClientServiceProvider::class);
});

it('binds the main client in the container', function () {
    $client = $this->app->make(LangGraphClientClass::class);

    expect($client)->toBeInstanceOf(LangGraphClientClass::class);
});

it('resolves the same instance as singleton', function () {
    $client1 = $this->app->make(LangGraphClientClass::class);
    $client2 = $this->app->make(LangGraphClientClass::class);

    expect($client1)->toBe($client2);
});

it('can use facade to access client', function () {
    config(['langgraph-client-php.api_key' => 'test-key']);

    $client = LangGraphClient::fromEnvironment();

    expect($client)->toBeInstanceOf(LangGraphClientClass::class);
});

it('publishes config file', function () {
    expect(config('langgraph-client-php'))->toBeArray();
});

it('has correct configuration structure', function () {
    $config = config('langgraph-client-php');

    expect($config)
        ->toBeArray()
        ->toHaveKeys(['api_key', 'base_url', 'timeout', 'connect_timeout', 'retries']);
});
