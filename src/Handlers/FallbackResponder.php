<?php


namespace Nicu\Handlers;

use Nicu\{
    Constants\HttpStatusCodeMessages,
    Constants\HttpStatusCodes
};

class FallbackResponder extends Handler
{
    public function __invoke()
    {
        list($payload, $status, $message) = func_get_args();
        $status = $status ?? HttpStatusCodes::OK;
        $message = $message ?? HttpStatusCodeMessages::OK;

        return ($this->container->get('response'))->withStatus($status)
            ->withHeader('Content-Type', 'application/json')
            ->write(
                json_encode(
                    [
                        'status' => $status,
                        'message' => $message,
                        'payload' => $payload
                    ]
                )
            );
    }
}
