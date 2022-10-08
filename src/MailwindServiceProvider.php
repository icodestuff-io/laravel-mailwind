<?php

namespace Icodestuff\Mailwind;

use Icodestuff\Mailwind\Commands\CompileMailTemplateCommand;
use Icodestuff\Mailwind\Commands\CreateMailTemplateCommand;
use Icodestuff\Mailwind\Commands\GenerateMailViewsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MailwindServiceProvider extends PackageServiceProvider
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
            ->hasCommands([
                GenerateMailViewsCommand::class,
                CreateMailTemplateCommand::class,
                CompileMailTemplateCommand::class,
            ]);
    }
}
