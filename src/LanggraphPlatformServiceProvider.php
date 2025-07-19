<?php

namespace Jason Tame\LanggraphPlatform;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Jason Tame\LanggraphPlatform\Commands\LanggraphPlatformCommand;

class LanggraphPlatformServiceProvider extends PackageServiceProvider
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
            ->hasCommand(LanggraphPlatformCommand::class);
    }
}
