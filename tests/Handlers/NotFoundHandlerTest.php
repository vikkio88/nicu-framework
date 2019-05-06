<?php


namespace Nicu\Tests\Handlers;

use Nicu\{
    Handlers\NotFoundHandler,
    Tests\Base\UnitTest
};
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class NotFoundHandlerTest extends UnitTest
{
    public function testInvoke()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('responder')->willReturn(function ($payload, $status, $message) {
            return $this->createMock(ResponseInterface::class);
        });
        $notFoundHandler = new NotFoundHandler($container);

        $response = $notFoundHandler->__invoke();
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
