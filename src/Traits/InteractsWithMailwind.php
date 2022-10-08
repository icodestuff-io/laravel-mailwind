<?php

namespace Icodestuff\Mailwind\Traits;

use Icodestuff\Mailwind\Facades\Mailwind;

trait InteractsWithMailwind
{
    public function view($view, array $data = []): self
    {
        $view = Mailwind::compile($view);

        return parent::view($view, $data);
    }
}
