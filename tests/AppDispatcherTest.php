<?php

namespace Betalabs\EngineWorkflowHelper\Tests;

use Betalabs\EngineWorkflowHelper\AbstractAppDispatcherWorkflow;
use Betalabs\EngineWorkflowHelper\Tests\Fixtures\DeleteDispatcher;
use Betalabs\EngineWorkflowHelper\Tests\Fixtures\GetDispatcher;
use Betalabs\EngineWorkflowHelper\Tests\Fixtures\PostDispatcher;
use Betalabs\EngineWorkflowHelper\Tests\Fixtures\PutDispatcher;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * HTTP method and listener params of each AppDispatcher workflow base
 */
class AppDispatcherTest extends TestCase
{
    public static function dispatchers()
    {
        return [
            'get' => [GetDispatcher::class, 'get', ['appRegistryId', 'uri']],
            'delete' => [DeleteDispatcher::class, 'delete', ['appRegistryId', 'uri']],
            'post' => [PostDispatcher::class, 'post', ['appRegistryId', 'uri', 'data']],
            'put' => [PutDispatcher::class, 'put', ['appRegistryId', 'uri', 'data']],
        ];
    }

    #[DataProvider('dispatchers')]
    public function testListenerMethodAndParams($class, $method, $params)
    {
        $dispatcher = resolve($class);

        $this->assertInstanceOf(AbstractAppDispatcherWorkflow::class, $dispatcher);
        $this->assertSame('App\Listeners\EngineListeners\AppDispatcher', $dispatcher::LISTENER_CLASS);
        $this->assertSame($method, $dispatcher::LISTENER_METHOD);
        $this->assertSame($params, $dispatcher->exposedListenerParams()->values()->all());
    }
}
