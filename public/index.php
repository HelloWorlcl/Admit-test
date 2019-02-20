<?php

use App\Kernel\Database\DatabaseConfiguration;
use App\Kernel\Database\Connection\MySQLConnection;
use App\Kernel\Routing\Router;
use App\Kernel\ServiceContainer\ServiceContainer;
use Symfony\Component\Dotenv\Dotenv;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__) . '/.env');

$router = new Router();
$container = new ServiceContainer();
$configuration = new DatabaseConfiguration(
    getenv('DB_HOST'),
    getenv('DB_PORT'),
    getenv('DB_NAME'),
    getenv('DB_USERNAME'),
    getenv('DB_PASSWORD')
);
$connection = new MySQLConnection($configuration);
$container->setSingleton(MySQLConnection::class, $connection);

try {
    $controller = $container->getObjectWithDependencies($router->getControllerPath());
    $controllerMethod = $router->getControllerMethod();
    $controllerArgs = $router->getControllerArgs();

    $router->setResponse($controller->$controllerMethod($controllerArgs));

    echo $router->getResponse();
} catch (\Exception $e) {
    echo $e->getMessage();

    return;
}
