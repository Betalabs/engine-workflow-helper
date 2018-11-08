<?php

namespace Betalabs\EngineWorkflowHelper\Enums;

use MyCLabs\Enum\Enum;

class WorkflowStepApproach extends Enum
{
    const SYNCHRONOUS = 'synchronous';
    const ASYNCHRONOUS = 'asynchronous';
}