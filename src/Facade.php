<?php

namespace Fomvasss\EpochtaService;

/**
 * @see \Spatie\Fractal\Fractal
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'epochta-sms';
    }
}
