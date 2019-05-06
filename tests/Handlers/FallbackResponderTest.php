<?php


namespace Nicu\Tests\Handlers;

use Nicu\{
    Constants\HttpStatusCodeMessages,
    Constants\HttpStatusCodes,
    Handlers\FallbackResponder,
    Tests\Base\UnitTest
};
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

class FallbackResponderTest extends UnitTest
{
    public function testInvoke()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('response')->willReturn(new Response());
        $fallbackResponder = new FallbackResponder($container);
        $status = HttpStatusCodes::OK;
        $message = HttpStatusCodeMessages::OK;
        $response = $fallbackResponder->__invoke([], $status, $message);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($status, $response->getStatusCode());
    }
}
