<?php

namespace Icodestuff\MailWind\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Icodestuff\MailWind\MailWind
 */
class MailWind extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Icodestuff\MailWind\MailWind::class;
    }
}
