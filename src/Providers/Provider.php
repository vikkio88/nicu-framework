<?php


namespace Nicu\Providers;

use DI\Bridge\Slim\App;

abstract class Provider
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    protected function bind($contract, $concrete)
    {
        $this->app->getContainer()->set($contract, $concrete);
    }

    abstract public function boot();
}
