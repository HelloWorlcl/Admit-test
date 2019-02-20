<?php

namespace App\Kernel\Routing;

use App\Controllers\AbstractController;
use App\Kernel\Routing\Exceptions\MethodIsNotAllowedException;
use App\Kernel\Routing\Exceptions\RouteIsNotFoundException;

class Router
{
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_METHOD_PATCH = 'PATCH';
    const HTTP_METHOD_DELETE = 'DELETE';

    /**
     * @var array
     */
    private $routes;

    /**
     * @var string
     */
    private $requestUri;

    /**
     * @var string
     */
    private $requestMethod;

    /**
     * @var string
     */
    private $requestQueryString;

    /**
     * @var mixed
     */
    private $response;

    public function __construct()
    {
        $this->routes = require_once('routes.php');
        $this->setRequestUri()
            ->setRequestMethod()
            ->setRequestQueryString();
    }

    private function setRequestUri(): Router
    {
        $this->requestUri = $_SERVER['REQUEST_URI'];

        return $this;
    }

    private function setRequestMethod(): Router
    {
        $this->requestMethod = $this->isFileWithCustomMethodSent()
            ? $_POST['_method']
            : $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        return $this;
    }

    private function isFileWithCustomMethodSent(): bool
    {
        return !empty($_FILES) && !empty($_POST['_method']);
    }

    private function setRequestQueryString(): Router
    {
        $this->requestQueryString = $_SERVER['QUERY_STRING'];

        return $this;
    }

    public function getControllerPath(): string
    {
        $path = $this->getRequestPath();

        if (array_key_exists($path, $this->routes)) {
            return $this->routes[$path];
        }

        throw new RouteIsNotFoundException('Route is not found');
    }

    private function getRequestPath(): string
    {
        $parsedUri = parse_url($this->requestUri);

        return $parsedUri['path'];
    }

    /**
     * @throws MethodIsNotAllowedException
     */
    public function getControllerMethod(): string
    {
        switch ($this->requestMethod) {
            case self::HTTP_METHOD_GET:
                return !empty($this->requestQueryString)
                    ? AbstractController::CONTROLLER_METHOD_GET
                    : AbstractController::CONTROLLER_METHOD_GET_ALL;
            case self::HTTP_METHOD_POST:
                return AbstractController::CONTROLLER_METHOD_POST;
            case self::HTTP_METHOD_PUT:
                return AbstractController::CONTROLLER_METHOD_PUT;
            case self::HTTP_METHOD_PATCH:
                return AbstractController::CONTROLLER_METHOD_PATCH;
            case self::HTTP_METHOD_DELETE:
                return AbstractController::CONTROLLER_METHOD_DELETE;
            default:
                throw new MethodIsNotAllowedException('Method is not allowed');
        }
    }

    public function getControllerArgs(): array
    {
        switch (true) {
            case $this->isMethodWithQueryString():
                return $this->buildArgsArray($this->getQueryStringParams());
            case !empty($_FILES):
                return $_POST;
            default:
                return json_decode(file_get_contents('php://input'), true);
        }
    }

    private function isMethodWithQueryString(): bool
    {
        return $this->requestMethod === self::HTTP_METHOD_GET || $this->requestMethod === self::HTTP_METHOD_DELETE;
    }

    private function getQueryStringParams(): array
    {
        return !empty($this->requestQueryString)
            ? explode('&', $this->requestQueryString)
            : [];
    }

    private function buildArgsArray(array $params): array
    {
        $args = [];

        foreach ($params as $param) {
            [$key, $value] = explode('=', $param);

            $args[$key] = $value;
        }

        return $args;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response): Router
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse(): string
    {
        $this->setDefaultHeaders();

        return json_encode($this->response);
    }

    private function setDefaultHeaders(): void
    {
        header('Content-Type: application/json');
    }
}
