<?php


namespace Nicu\Tests\Actions\Stubs;

use Nicu\Actions\ApiAction;
use Nicu\Exceptions\NotFound;

class TestNotFoundAction extends ApiAction
{
    /**
     * @return array
     * @throws NotFound
     */
    protected function action(): array
    {
        throw new NotFound();
    }
}
