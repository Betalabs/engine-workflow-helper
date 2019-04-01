<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\PriceGroup;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractPriceGroup;

class Created extends AbstractPriceGroup
{
    const EVENT_CLASS = '';
    const EVENT_METHOD = '';
    const EVENT_NAME = 'PriceGroup.Created';
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'put';
}