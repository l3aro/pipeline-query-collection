<?php

namespace Baro\PipelineQueryCollection\Commands;

use Illuminate\Console\Command;

class PipelineQueryCollectionCommand extends Command
{
    public $signature = 'pipeline-query-collection';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
