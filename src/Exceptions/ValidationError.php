<?php


namespace Nicu\Exceptions;

use Nicu\Constants\HttpStatusCodes;

class ValidationError extends NicuException
{
    protected $message = 'Invalid Body';
    protected $status = HttpStatusCodes::UNPROCESSABLE_ENTITY;
}
