<?php

$router->setNamespace('\Controllers');
$router->post('/orders/(\w+)/items', 'ControllerOrderUpdate@post');
$router->post('/orders', 'ControllerOrderCreate@post');
$router->post('/orders/(\w+)/done', 'ControllerOrderSetDone@post');
$router->get('/orders/(\w+)', 'ControllerOrderGetOne@get');
$router->get('/orders', 'ControllerOrderGetAll@get');

?>