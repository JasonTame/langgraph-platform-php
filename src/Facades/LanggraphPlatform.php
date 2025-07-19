<?php

namespace LangGraphPlatform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LangGraphPlatform\LangGraphPlatform
 */
class LangGraphPlatform extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LangGraphPlatform\LangGraphPlatform::class;
    }
}
