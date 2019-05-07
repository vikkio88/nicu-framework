<?php


namespace Nicu\Console;

abstract class ConsoleCommand
{
    protected $mandatoryArgs = [];
    protected $acceptedArgs = [];
    protected $args = [];

    public function __construct(array $args)
    {
        $this->parseArgs($args);
    }

    public abstract function run();

    private function parseArgs(array $args)
    {
        if (empty($this->acceptedArgs) || empty($args)) {
            return;
        }

        foreach ($args as $arg) {
            // add some way to get args as more than a single bool
            if (in_array($arg, $this->acceptedArgs)) {
                $this->args[$arg] = true;
            }
        }

        // check if $mandatory are all in $args
    }

    protected function getArg($key, $fallback = false)
    {
        return isset($this->args[$key]) ? $this->args[$key] : $fallback;
    }
}
