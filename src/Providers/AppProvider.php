<?php


namespace Nicu\Providers;

class AppProvider extends Provider
{
    public function boot()
    {
        $this->bind(ExampleInterface::class, function () {
            return new ExampleImplement();
        });
    }
}
