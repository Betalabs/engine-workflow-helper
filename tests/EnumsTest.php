<?php

namespace Betalabs\EngineWorkflowHelper\Tests;

use Betalabs\EngineWorkflowHelper\Enums\VirtualEntity;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionApproach;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowConditionOperator;
use Betalabs\EngineWorkflowHelper\Enums\WorkflowStepApproach;

/**
 * Values sent to Engine: changing any of them breaks the workflows already
 * created there
 */
class EnumsTest extends \PHPUnit\Framework\TestCase
{
    public function testWorkflowConditionOperator()
    {
        $this->assertSame([
            'EQUAL' => '=',
            'DIFFERENT' => '!=',
            'GREATER_THAN' => '>',
            'GREATER_THAN_OR_EQUAL_TO' => '>=',
            'LESS_THAN' => '<',
            'LESS_THAN_OR_EQUAL_TO' => '<=',
        ], WorkflowConditionOperator::toArray());
    }

    public function testWorkflowConditionApproach()
    {
        $this->assertSame(['AND' => 'and', 'OR' => 'or'], WorkflowConditionApproach::toArray());
    }

    public function testWorkflowStepApproach()
    {
        $this->assertSame([
            'SYNCHRONOUS' => 'synchronous',
            'ASYNCHRONOUS' => 'asynchronous',
        ], WorkflowStepApproach::toArray());
    }

    public function testVirtualEntitySlugs()
    {
        $this->assertSame('items', VirtualEntity::ITEMS_SLUG);
        $this->assertSame('item', VirtualEntity::ITEM_SLUG);
        $this->assertSame('item-inventory', VirtualEntity::ITEM_INVENTORY);
        $this->assertSame('item-price', VirtualEntity::ITEM_PRICE);
    }
}
