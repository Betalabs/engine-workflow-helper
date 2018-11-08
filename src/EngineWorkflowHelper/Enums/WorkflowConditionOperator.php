<?php

namespace Betalabs\EngineWorkflowHelper\Enums;

use MyCLabs\Enum\Enum;

class WorkflowConditionOperator extends Enum
{
    const EQUAL = '=';
    const DIFFERENT = '!=';
    const GREATER_THAN = '>';
    const GREATER_THAN_OR_EQUAL_TO = '>=';
    const LESS_THAN = '<';
    const LESS_THAN_OR_EQUAL_TO = '<=';
}