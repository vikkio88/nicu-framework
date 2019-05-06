<?php


namespace Nicu\Exceptions;

use Exception;
use Nicu\Constants\HttpStatusCodeMessages;
use Nicu\Constants\HttpStatusCodes;

abstract class NicuException extends Exception
{
    protected $status = HttpStatusCodes::INTERNAL_SERVER_ERROR;
    protected $message = HttpStatusCodeMessages::INTERNAL_SERVER_ERROR;
    private $payload = null;

    public function __construct(array $payload = null, int $status = null, string $message = null)
    {
        $this->payload = $payload;
        parent::__construct($message ?? $this->message, $status ?? $this->status);
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
