<?php

namespace Nicu\Tests\Actions\Stubs;

use Exception;
use Nicu\Actions\ApiAction;

class TestGenericExceptionAction extends ApiAction
{
    /**
     * @return array
     * @throws Exception
     */
    protected function action(): array
    {
        throw new Exception('some stuff happened');
    }
}
