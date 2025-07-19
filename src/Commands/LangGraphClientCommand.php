<?php

declare(strict_types=1);

namespace JasonTame\LangGraphClient\Commands;

use Illuminate\Console\Command;

class LangGraphClientCommand extends Command
{
    public $signature = 'langgraph-client-php';

    public $description = 'LangGraph Client PHP SDK command';

    public function handle(): int
    {
        $this->comment('LangGraph Client PHP SDK');

        return self::SUCCESS;
    }
}
