<?php

namespace Jason Tame\LanggraphPlatform\Commands;

use Illuminate\Console\Command;

class LanggraphPlatformCommand extends Command
{
    public $signature = 'langgraph-platform-php';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
