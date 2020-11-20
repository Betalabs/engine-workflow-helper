<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Product;
use Betalabs\EngineWorkflowHelper\Product\Updated;

class UpdatedTest extends AbstractProduct
{
    public function testUpdate()
    {
        $this->makeTest(
            'App\Services\VirtualEntityRecord\Update',
            'fireEdEngineEvents',
            'App\Listeners\EngineListeners\AppDispatcher',
            'put',
            Updated::class
        );
    }
}
