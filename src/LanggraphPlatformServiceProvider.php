<?php

declare(strict_types=1);

namespace LangGraphPlatform;

use LangGraphPlatform\Commands\LangGraphPlatformCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LangGraphPlatformServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('langgraph-platform-php')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_langgraph_platform_php_table')
            ->hasCommand(LangGraphPlatformCommand::class);
    }

    public function registeringPackage(): void
    {
        // Register the main LangGraph Platform client as a singleton
        $this->app->singleton(LangGraphPlatform::class, function ($app) {
            return new LangGraphPlatform();
        });

        // Bind the client to the container using the interface if needed
        $this->app->bind('langgraph-platform', function ($app) {
            return $app->make(LangGraphPlatform::class);
        });
    }

    public function packageBooted(): void
    {
        // Additional setup can be done here if needed
    }
}
