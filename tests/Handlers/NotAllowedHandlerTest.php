<?php


namespace Nicu\Tests\Handlers;

use Nicu\Handlers\NotAllowedHandler;
use Nicu\Tests\Base\UnitTest;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class NotAllowedHandlerTest extends UnitTest
{
    public function testInvoke()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('responder')->willReturn(function ($payload, $status, $message) {
            return $this->createMock(ResponseInterface::class);
        });
        $notFoundHandler = new NotAllowedHandler($container);

        $response = $notFoundHandler->__invoke();
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
