<?php

namespace Fomvasss\EpochtaService\Facade;

/**
 * Class Sms
 *
 * @package Fomvasss\EpochtaService
 */
class Sms extends \Illuminate\Support\Facades\Facade
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
