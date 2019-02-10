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
        $this->requestUri = $_SERVER['REQUEST_URI'];
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->requestQueryString = $_SERVER['QUERY_STRING'];
    }

    /**
     * @throws RouteIsNotFoundException
     */
    public function handle(): void
    {
        $path = $this->getRequestPath();

        if (array_key_exists($path, $this->routes)) {
            $controller = new $this->routes[$path];
            $controllerMethod = $this->getControllerMethod();
            $controllerArgs = $this->getControllerArgs();

            $this->response = $controller->$controllerMethod($controllerArgs);
        } else {
            throw new RouteIsNotFoundException('Route is not found');
        }
    }

    private function getRequestPath(): string
    {
        $parsedUri = parse_url($this->requestUri);

        return $parsedUri['path'];
    }

    /**
     * @throws MethodIsNotAllowedException
     */
    private function getControllerMethod(): string
    {
        //TODO: maybe also check if a controller has the method
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

    private function getControllerArgs(): array
    {
        if ($this->requestMethod === self::HTTP_METHOD_GET || $this->requestMethod === self::HTTP_METHOD_DELETE) {
            $params = !empty($this->requestQueryString)
                ? explode('&', $this->requestQueryString)
                : [];

            return $this->buildArgsArray($params);
        }

        return json_decode(file_get_contents('php://input'), true);
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