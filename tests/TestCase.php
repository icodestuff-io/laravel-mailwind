<?php

namespace Icodestuff\Mailwind\Tests;

use Icodestuff\Mailwind\MailwindServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            MailwindServiceProvider::class,
        ];
    }
}
