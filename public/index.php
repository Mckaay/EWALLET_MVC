<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Login', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('login', ['controller' => 'Login', 'action' => 'index']);
$router->add('register', ['controller' => 'Register', 'action' => 'index']);
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('register/activate/{token:[\da-f]+}', ['controller' => 'Register', 'action' => 'activate']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('settings', ['controller' => 'Settings', 'action' => 'index']);
    
$router->dispatch($_SERVER['QUERY_STRING']);
