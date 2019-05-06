<?php

namespace Nicu\Exceptions;

use Nicu\Constants\{
    HttpStatusCodeMessages, HttpStatusCodes
};

class Unauthorized extends NicuException
{
    protected $message = HttpStatusCodeMessages::UNAUTHORIZED;
    protected $status = HttpStatusCodes::UNAUTHORIZED;
}
