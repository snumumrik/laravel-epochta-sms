<?php

namespace Fomvasss\EpochtaService\Events;

class BeforeSendingSmsEvent
{
    public $attributes = [];

    /**
     * BeforeSendingSmsEvent constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }
}
