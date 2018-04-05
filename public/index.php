<?php
/**
 * Created by PhpStorm.
 * User: avoge
 * Date: 04/04/2018
 * Time: 20:56
 */

$app = new \Slim\App;
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