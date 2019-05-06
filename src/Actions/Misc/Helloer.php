<?php

namespace Nicu\Actions\Misc;

use Nicu\Actions\ApiAction;

class Helloer extends ApiAction
{
    protected function action(): array
    {
        return [
            'hello' => $this->get('name', 'ciao')
        ];
    }
}
