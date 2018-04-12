<?php
/**
 * Created by PhpStorm.
 * User: avoge
 * Date: 05/04/2018
 * Time: 10:53
 */

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$appConfig = $app->getContainer();

//--------------------------------------SECURITY----------------------------------------------------------------------//
// Configuramos la seguridad de las rutas mediante Json Web Tokens


//--------------------------------------ROUTES------------------------------------------------------------------------//

$app->get(
    '/hello/{name}',
    'PWBox\controller\HelloController'
)->add('PWBox\controller\middleware\HelloMiddleware');

$app->post('/user', 'PWBox\controller\PostUserController');

// -------------------------NAVIGATION
/*
//Landing Page
$app->group('/', function(){

});

//Profile Page
$app->group('/profile', function(){

});

//Dashboard Page
$app->group('/dashboard', function(){

});

//Settings Page
$app->group('/settings', function(){

});

// -------------------------API

//User
$app->group('/user', function($request, $response, $args){
    require __DIR__.'/api_routes/apiUserRoutes.php';
});

//Folder
$app->group('/folder', function ($request, $response, $args){
    require __DIR__.'/api_routes/apiFolderRoutes.php';
});
*/