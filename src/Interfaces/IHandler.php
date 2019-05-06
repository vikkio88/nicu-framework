<?php


namespace Nicu\Interfaces;

use Psr\Container\ContainerInterface;

interface IHandler
{
    public function __construct(ContainerInterface $container);
    public function __invoke();
}
