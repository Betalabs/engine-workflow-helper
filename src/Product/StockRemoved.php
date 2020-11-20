<?php

namespace Betalabs\EngineWorkflowHelper\Product;

class StockRemoved extends AbstractStock
{
    const LISTENER_CLASS = 'App\Listeners\EngineListeners\AppDispatcher';
    const LISTENER_METHOD = 'put';
    const EVENT_CLASS = '';
    const EVENT_METHOD = '';
    const EVENT_NAME = 'Item.Stock.Movement.Remove.Removed';
}
