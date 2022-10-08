<?php

namespace Icodestuff\Mailwind\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Icodestuff\Mailwind\Mailwind
 */
class Mailwind extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Icodestuff\Mailwind\Mailwind::class;
    }
}
