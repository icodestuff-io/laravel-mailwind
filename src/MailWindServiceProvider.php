<?php

namespace Icodestuff\MailWind;

use Icodestuff\MailWind\Commands\MailWindCompileCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MailWindServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-mailwind')
            ->hasConfigFile('mailwind')
            ->hasViews('mailwind')
            ->hasCommand(MailWindCompileCommand::class);
    }
}