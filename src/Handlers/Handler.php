<?php

namespace Nicu\Handlers;

use Nicu\Interfaces\IHandler;
use Psr\Container\ContainerInterface;

abstract class Handler implements IHandler
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
