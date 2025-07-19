<?php

declare(strict_types=1);

namespace LangGraphPlatform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for LangGraph Platform SDK.
 *
 * @method static \LangGraphPlatform\Resources\AssistantsClient assistants()
 * @method static \LangGraphPlatform\Resources\ThreadsClient threads()
 * @method static \LangGraphPlatform\Resources\RunsClient runs()
 * @method static \LangGraphPlatform\Resources\CronsClient crons()
 * @method static \LangGraphPlatform\Resources\StoreClient store()
 * @method static \LangGraphPlatform\Http\Client getHttpClient()
 * @method static \LangGraphPlatform\LangGraphPlatform configure(array $config)
 * @method static \LangGraphPlatform\LangGraphPlatform create(array $config = [])
 * @method static \LangGraphPlatform\LangGraphPlatform fromEnvironment()
 *
 * @see \LangGraphPlatform\LangGraphPlatform
 */
class LangGraphPlatform extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LangGraphPlatform\LangGraphPlatform::class;
    }
}
