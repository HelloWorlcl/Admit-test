<?php

use App\Kernel\Routing\Router;
use Symfony\Component\Dotenv\Dotenv;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$router = new Router();
$dotenv = new Dotenv();
$dotenv->load('./../.env');

try {
    $router->handle();
} catch (\Exception $e) {
    echo $e->getMessage();
    return;
}

echo $router->getResponse();
