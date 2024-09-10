<?php

namespace Signalmetrics\Signal;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Signalmetrics\Signal\Skeleton\SkeletonClass
 */
class SignalFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'signal';
    }
}
