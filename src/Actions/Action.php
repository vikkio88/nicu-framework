<?php


namespace Nicu\Actions;

use Nicu\{
    Exceptions\NicuException,
    Interfaces\Actions\ICallableAction,
    Constants\HttpStatusCodes,
    Constants\HttpStatusCodeMessages
};
use Psr\{
    Http\Message\RequestInterface,
    Http\Message\ResponseInterface
};
use Exception;
use Slim\Http\Request;

abstract class Action implements ICallableAction
{
    protected $status = HttpStatusCodes::OK;
    protected $message = HttpStatusCodeMessages::OK;
    protected $payload = [];

    protected $request;
    protected $response;
    protected $args = [];

    protected $requestBody = null;
    protected $queryParams = null;

    public function get($key, $default = null)
    {
        //json body will override the query params that override the uri args
        $values = array_merge(
            $this->args,
            $this->getQueryParams(),
            $this->getRequestBody()
        );
        return $values[$key] ?? $default;
    }

    public function getRequestBody()
    {
        if (is_null($this->requestBody)) {
            $json = $this->request instanceof Request ?
                $this->request->getBody() : "";
            $this->requestBody = json_decode($json, true) ?? [];
        }
        return $this->requestBody;
    }

    public function getHeader($key)
    {
        return $this->request instanceof Request ?
            $this->request->getHeaderLine($key) : null;
    }

    public function getQueryParams()
    {
        if (is_null($this->queryParams)) {
            $this->queryParams = $this->request instanceof Request ?
                $this->request->getQueryParams() : [];
        }

        return $this->queryParams;
    }

    private function getArgsFromRequest(RequestInterface $request): array
    {
        $attributes = $request->getAttributes();
        return isset($attributes['route']) ?
            ($attributes['route'])->getArguments() : [];
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $this->getArgsFromRequest($request);
        return $this->execute();
    }

    public function execute()
    {
        $this->init();
        try {
            $this->payload = $this->action();
        } catch (NicuException $e) {
            $this->manageInternalException($e);
        } catch (Exception $e) {
            $this->manageException($e);
        }
        $this->postAction();

        return $this->respond($this->payload, $this->status, $this->message);
    }

    protected function manageInternalException(NicuException $exception)
    {
        $this->status = $exception->getCode();
        $this->message = $exception->getMessage();
        $this->payload = $exception->getPayload();
    }

    protected function manageException(Exception $exception)
    {
        $this->status = HttpStatusCodes::INTERNAL_SERVER_ERROR;
        $this->message = HttpStatusCodeMessages::INTERNAL_SERVER_ERROR;
        $this->payload = [
            'error' => $exception->getMessage()
        ];
    }

    abstract protected function init();

    /**
     * @throws NicuException|Exception
     */
    abstract protected function action(): array;

    abstract protected function postAction();

    protected function respond($payload, $status, $message): ResponseInterface
    {
        return $this->response->withStatus($status)
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
