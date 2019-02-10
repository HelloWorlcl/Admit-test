<?php

use App\Kernel\Routing\Router;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$router = new Router();

try {
    $router->handle();
} catch (\Exception $e) {
    echo $e->getMessage();
    return;
}

echo $router->getResponse();
