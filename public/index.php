<?php
/**
 * Created by PhpStorm.
 * User: avoge
 * Date: 04/04/2018
 * Time: 20:56
 */
require __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__.'/../app/settings.php';

// En entorno de produccion, los ficheros *.env deberian ignorarse del control de versiones por seguridad.
$dotenv = new Dotenv\Dotenv(__DIR__.'/../app', 'config.env');
$dotenv->load();

$app = new \Slim\App($settings);

require __DIR__.'/../app/routes.php';
try {
    $app->run();
} catch (\Slim\Exception\MethodNotAllowedException $e) {
    echo $e->getMessage();
    http_response_code(500);
} catch (\Slim\Exception\NotFoundException $e) {
    echo $e->getMessage();
    http_response_code(500);
} catch (Exception $e) {
    echo $e->getMessage();
    http_response_code(500);
}