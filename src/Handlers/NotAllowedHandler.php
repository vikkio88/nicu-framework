<?php

namespace Nicu\Handlers;

use Nicu\Constants\{
    HttpStatusCodeMessages,
    HttpStatusCodes
};

class NotAllowedHandler extends HttpErrorHandler
{
    protected $status = HttpStatusCodes::NOT_ALLOWED;
    protected $message = HttpStatusCodeMessages::NOT_ALLOWED;
    protected $payload = [];
}
