<?php


use Nicu\Actions\Misc\Helloer;
use Nicu\Actions\Misc\Ping;

return [
    'routes' => [
        [
            'route' => '/ping',
            'method' => 'get',
            'action' => Ping::class
        ],
        [
            'route' => '/hello/{name}',
            'method' => 'get',
            'action' => Helloer::class
        ],
    ]
];
