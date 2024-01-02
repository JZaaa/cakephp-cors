<?php

use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cors\Routing\Middleware\CorsMiddleware;


Configure::load('Cors.default', 'default');

$defaultConfig = (array) Configure::consume('Cors-default');
$personalConfig = (array) Configure::consume('Cors');
$config = array_merge($defaultConfig, $personalConfig);


Configure::write('Cors', $config);


if ($config['exceptionRenderer'] && Configure::read('Error.exceptionRenderer') != $config['exceptionRenderer']) {
    Configure::write('Error.baseExceptionRenderer', (!empty($config['exceptionRenderer']) ? $config['exceptionRenderer'] : Configure::read('Error.exceptionRenderer')) ?? 'Cake\Error\Renderer\WebExceptionRenderer');
}

/**
 * Middleware
 */
EventManager::instance()->on('Server.buildMiddleware',
    function ($event, $middleware) {
        try {
            $middleware->insertBefore(RoutingMiddleware::class, new CorsMiddleware());
        } catch (\LogicException $exception) {
            $middleware->add(new CorsMiddleware());
        }
    }
);
