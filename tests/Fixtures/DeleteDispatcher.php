<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Fixtures;

use Betalabs\EngineWorkflowHelper\AppDispatcher\Delete;

class DeleteDispatcher extends Delete
{
    use ExposesListenerParams;
}
