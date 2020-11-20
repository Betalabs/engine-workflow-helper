<?php

namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AppDispatcher;


use Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\AbstractAppDispatcherWorkflow;

abstract class Delete extends AbstractAppDispatcherWorkflow
{
    const LISTENER_METHOD = 'delete';
}