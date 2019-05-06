<?php


namespace Nicu\Actions\Misc;

use Nicu\Actions\ApiAction;
use Noodlehaus\Config;

class Ping extends ApiAction
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    protected function action(): array
    {
        return [
            'version' => $this->config->get('app.version'),
            'stuff' => $this->config->get('stuff'), // this is coming from .env
            'pong' => 'pong'
        ];
    }
}
