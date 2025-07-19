<?php

namespace LanggraphPlatform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LanggraphPlatform\LanggraphPlatform
 */
class LanggraphPlatform extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LanggraphPlatform\LanggraphPlatform::class;
    }
}
