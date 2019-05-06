<?php

namespace Nicu\Handlers;

use Nicu\Constants\{
    HttpStatusCodeMessages,
    HttpStatusCodes
};

class NotFoundHandler extends HttpErrorHandler
{
    protected $status = HttpStatusCodes::NOT_FOUND;
    protected $message = HttpStatusCodeMessages::NOT_FOUND;
    protected $payload = null;
}
