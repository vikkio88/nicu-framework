<?php


namespace Nicu\Tests\Actions;

use Nicu\{
    Constants\HttpStatusCodes,
    Tests\Actions\Stubs\TestFailingAction,
    Tests\Actions\Stubs\TestNotFoundAction,
    Tests\Actions\Stubs\TestSuccessAction,
    Tests\Actions\Stubs\TestUnauthorizedAction,
    Tests\Actions\Stubs\TestGenericExceptionAction,
    Tests\Base\UnitTest
};
use Slim\Http\{
    Request, Response
};

class ActionTest extends UnitTest
{

    public function getMockRequest()
    {
        $request = $this->createMock(Request::class);
        $response = new Response();
        return [
            $request,
            $response
        ];
    }

    public function testGetWithEmptyParameters()
    {
        $successAction = (new TestSuccessAction());
        $this->assertNotEmpty($successAction->get('param', 'fallback'));
        $this->assertEquals(
            'fallback',
            $successAction->get('param', 'fallback')
        );
    }

    public function testGetWithEmptyRequestButArgs()
    {
        $fallback = 'fallback';
        $paramName = 'param';
        $successAction = (new TestSuccessAction());
        $this->setProtectedProperty($successAction, 'args', [
            $paramName => 'not the fallback'
        ]);
        $parameter = $successAction->get($paramName, $fallback);
        $this->assertNotEmpty($parameter);
        $this->assertNotEquals($fallback, $parameter);
    }

    public function testGetHeader()
    {
        list($request) = $this->getMockRequest();
        $request->method('getHeaderLine')->with('example')->willReturn('stuff');
        $successAction = (new TestSuccessAction());
        $this->setProtectedProperty($successAction, 'request', $request);

        $this->assertEquals('stuff', $successAction->getHeader('example'));
    }

    /**
     * @dataProvider actionStubsProvider
     * @param $actionClass
     * @param $expectedResult
     */
    public function testInvokeWithMultipleActions($actionClass, $expectedResult)
    {
        list($request, $response) = $this->getMockRequest();
        $action = (new $actionClass());
        $result = $action->__invoke($request, $response, []);
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals($expectedResult, $result->getStatusCode());
    }

    public function actionStubsProvider()
    {
        return [
            [
                TestSuccessAction::class,
                HttpStatusCodes::OK
            ],
            [
                TestFailingAction::class,
                HttpStatusCodes::UNPROCESSABLE_ENTITY
            ],
            [
                TestNotFoundAction::class,
                HttpStatusCodes::NOT_FOUND
            ],
            [
                TestUnauthorizedAction::class,
                HttpStatusCodes::UNAUTHORIZED
            ],
            [
                TestGenericExceptionAction::class,
                HttpStatusCodes::INTERNAL_SERVER_ERROR
            ],
        ];
    }
}
