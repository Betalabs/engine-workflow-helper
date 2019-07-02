<?php


namespace Betalabs\EngineWorkflowHelper\EngineWorkflowHelper\Order;

class UpdateMultipleStatuses extends AbstractUpdateStatus
{
    const LISTENER_METHOD = 'changeMultipleRaw';
    const ID_PARAM = 'modelIds';
    const ORDER_ID_PARAM = 'orderIds';

}