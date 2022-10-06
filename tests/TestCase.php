<?php

namespace Icodestuff\MailWind\Tests;

use Icodestuff\MailWind\MailWindServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            MailWindServiceProvider::class,
        ];
    }
}
