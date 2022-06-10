<?php

namespace Baro\PipelineQueryCollection;

use Illuminate\Http\Request;

abstract class BaseSort
{
    public function __construct()
    {
        $this->request = app(Request::class);
    }
}
