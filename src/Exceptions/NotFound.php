<?php

namespace Nicu\Exceptions;

use Nicu\Constants\{
    HttpStatusCodeMessages, HttpStatusCodes
};

class NotFound extends NicuException
{
    protected $message = HttpStatusCodeMessages::NOT_FOUND;
    protected $status = HttpStatusCodes::NOT_FOUND;
}
