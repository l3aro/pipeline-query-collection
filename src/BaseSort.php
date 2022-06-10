<?php

namespace Baro\PipelineQueryCollection;

use Illuminate\Http\Request;

abstract class BaseSort
{
    protected Request $request;

    public function __construct()
    {
        $this->request = app(Request::class);
    }
}
