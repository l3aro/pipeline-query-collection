<?php

namespace Baro\PipelineQueryCollection\Enums;

enum TrashOptionEnum: string
{
    case WITH = 'with';
    case WITHOUT = 'without';
    case ONLY = 'only';
}
