<?php


namespace Nicu\Tests\Actions\Stubs;

use Nicu\Actions\ApiAction;

class TestSuccessAction extends ApiAction
{
    protected function action(): array
    {
        return [
            'success' => true
        ];
    }
}
