<?php

namespace Betalabs\EngineWorkflowHelper\PriceGroup;

use Betalabs\EngineWorkflowHelper\AbstractPriceGroup;

class Deleted extends AbstractPriceGroup
{
    const EVENT_CLASS = '';
    const EVENT_METHOD = '';
    const EVENT_NAME = 'PriceGroup.Deleted';
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'put';
}
