<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Product;


use Betalabs\EngineWorkflowHelper\Product\StockAdded;

class StockAddedTest extends AbstractStock
{
    public function testStockAdded()
    {
        $this->makeTest(
            'App\Listeners\EngineListeners\AppDispatcher',
            'put',
            'Item.Stock.Movement.Add.Added',
            StockAdded::class
        );
    }
}
