<?php

namespace Nicu\Tests\Actions\Stubs;

use Nicu\Actions\ApiAction;
use Nicu\Exceptions\Unauthorized;

class TestUnauthorizedAction extends ApiAction
{
    /**
     * @return array
     * @throws Unauthorized
     */
    protected function action(): array
    {
        throw new Unauthorized();
    }
}
