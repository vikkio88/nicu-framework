<?php


namespace Nicu\Tests\Actions\Stubs;

use Nicu\Actions\ApiAction;
use Nicu\Exceptions\ValidationError;

class TestFailingAction extends ApiAction
{
    /**
     * @return array
     * @throws ValidationError
     */
    protected function action(): array
    {
        throw new ValidationError();
    }
}
