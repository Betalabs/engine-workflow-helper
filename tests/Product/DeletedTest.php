<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Product;


use Betalabs\EngineWorkflowHelper\Product\Deleted;

class DeletedTest extends AbstractProduct
{
    public function testDelete()
    {
        $this->makeTest(
            'App\Observers\VirtualEntityRecord\VirtualEntityRecordObserver',
            'deleted',
            'App\Listeners\EngineListeners\AppDispatcher',
            'delete',
            Deleted::class
        );
    }
}
