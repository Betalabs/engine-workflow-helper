<?php

namespace Betalabs\EngineWorkflowHelper\PriceGroup;


use Betalabs\EngineWorkflowHelper\AbstractPriceGroup;

class Updated extends AbstractPriceGroup
{
    const EVENT_CLASS = '';
    const EVENT_METHOD = '';
    const EVENT_NAME = 'PriceGroup.Updated';
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'put';
}
