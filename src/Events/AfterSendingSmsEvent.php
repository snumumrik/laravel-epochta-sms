<?php

namespace Fomvasss\EpochtaService\Events;

class AfterSendingSmsEvent
{
    public $attributes = [];

    public $sendingResult = [];

    public $model;

    /**
     * AfterSendingSmsEvent constructor.
     *
     * @param array $attributes
     * @param array $sendingResult
     * @param null $model
     */
    public function __construct(array $attributes, array $sendingResult, $model = null)
    {
        $this->attributes = $attributes;
        $this->sendingResult = $sendingResult;
        $this->smodel = $model;
    }
}
