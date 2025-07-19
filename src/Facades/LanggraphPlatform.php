<?php

namespace Jason Tame\LanggraphPlatform\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jason Tame\LanggraphPlatform\LanggraphPlatform
 */
class LanggraphPlatform extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Jason Tame\LanggraphPlatform\LanggraphPlatform::class;
    }
}
