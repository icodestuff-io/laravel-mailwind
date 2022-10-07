<?php

namespace Icodestuff\MailWind\Traits;

use Icodestuff\MailWind\Facades\MailWind;

trait InteractsWithMailWind
{
    public function view($view, array $data = []): self
    {
        $view = MailWind::compile($view);

        return parent::view($view, $data);
    }
}
