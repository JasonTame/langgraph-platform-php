<?php

declare(strict_types=1);

namespace JasonTame\LangGraphClient;

use JasonTame\LangGraphClient\Commands\LangGraphClientCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LangGraphClientServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('langgraph-client-php')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_langgraph_client_php_table')
            ->hasCommand(LangGraphClientCommand::class);
    }

    public function registeringPackage(): void
    {
        // Register the main LangGraph Client as a singleton
        $this->app->singleton(LangGraphClient::class, function ($app) {
            return new LangGraphClient;
        });

        // Bind the client to the container using the interface if needed
        $this->app->bind('langgraph-client', function ($app) {
            return $app->make(LangGraphClient::class);
        });
    }

    public function packageBooted(): void
    {
        // Additional setup can be done here if needed
    }
}
