<?php

namespace Betalabs\EngineWorkflowHelper\AppDispatcher;


use Betalabs\EngineWorkflowHelper\AbstractAppDispatcherWorkflow;

abstract class Delete extends AbstractAppDispatcherWorkflow
{
    const LISTENER_METHOD = 'delete';
}
