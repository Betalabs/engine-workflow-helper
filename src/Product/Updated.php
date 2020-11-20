<?php

namespace Betalabs\EngineWorkflowHelper\Product;

class Updated extends AbstractProduct
{

    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'put';
    const EVENT_CLASS = 'App\Services\VirtualEntityRecord\Update';
    const EVENT_METHOD = 'fireEdEngineEvents';

}
