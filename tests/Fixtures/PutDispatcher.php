<?php

namespace Betalabs\EngineWorkflowHelper\Tests\Fixtures;

use Betalabs\EngineWorkflowHelper\AppDispatcher\Put;

class PutDispatcher extends Put
{
    use ExposesListenerParams;
}
