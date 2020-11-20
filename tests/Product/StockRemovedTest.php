<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Product;

use Betalabs\EngineWorkflowHelper\Product\StockRemoved;

class StockRemovedTest extends AbstractStock
{
    public function testStockRemoved()
    {
        $this->makeTest(
            'App\Listeners\EngineListeners\AppDispatcher',
            'put',
            'Item.Stock.Movement.Remove.Removed',
            StockRemoved::class
        );
    }
}
