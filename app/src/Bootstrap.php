<?php declare(strict_types = 1);

namespace Pizza;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL & ~E_DEPRECATED);

$environment = 'development';

// Create Router instance
$router = new \Bramus\Router\Router();

// Include the Router class
require_once __DIR__ . '/Configs.php';
require_once __DIR__ . '/CustomExceptions.php';
//require_once __DIR__ . '/Models.php';
require_once __DIR__ . '/Services.php';
require_once __DIR__ . '/Controllers.php';
require_once __DIR__ . '/Router.php';

header('Content-Type: application/json');
$router->run();
