<?php

namespace Betalabs\EngineWorkflowHelper\Order;

class UpdateStatus extends AbstractUpdateStatus
{
    const LISTENER_METHOD = 'changeRaw';
    const ID_PARAM = 'modelId';
    const ORDER_ID_PARAM = 'orderId';
}
