<?php


namespace Nicu\Handlers;

abstract class HttpErrorHandler extends Handler
{
    protected $status;
    protected $message;
    protected $payload;

    public function __invoke()
    {
        return $this->container->get('responder')(
            $this->payload,
            $this->status,
            $this->message
        );
    }
}
