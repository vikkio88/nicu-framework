<?php


namespace Nicu\Interfaces\Actions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ICallableAction
{
    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface;
}
