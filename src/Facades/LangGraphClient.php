<?php

declare(strict_types=1);

namespace JasonTame\LangGraphClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for LangGraph Client SDK.
 *
 * @method static \JasonTame\LangGraphClient\Resources\AssistantsClient assistants()
 * @method static \JasonTame\LangGraphClient\Resources\ThreadsClient threads()
 * @method static \JasonTame\LangGraphClient\Resources\RunsClient runs()
 * @method static \JasonTame\LangGraphClient\Resources\CronsClient crons()
 * @method static \JasonTame\LangGraphClient\Resources\StoreClient store()
 * @method static \JasonTame\LangGraphClient\Http\Client getHttpClient()
 * @method static \JasonTame\LangGraphClient\LangGraphClient configure(array $config)
 * @method static \JasonTame\LangGraphClient\LangGraphClient create(array $config = [])
 * @method static \JasonTame\LangGraphClient\LangGraphClient fromEnvironment()
 *
 * @see \JasonTame\LangGraphClient\LangGraphClient
 */
class LangGraphClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JasonTame\LangGraphClient\LangGraphClient::class;
    }
}
