<?php

namespace Betalabs\EngineWorkflowHelper\Product;

class Deleted extends AbstractProduct
{

    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'delete';
    const EVENT_CLASS = 'App\Observers\VirtualEntityRecord\VirtualEntityRecordObserver';
    const EVENT_METHOD = 'deleted';

}
