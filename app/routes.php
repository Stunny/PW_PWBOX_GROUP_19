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

// -------------------------NAVIGATION

//Landing Page
$app->group('/', function(){
  //Todo: ruta /
  echo "Hello";
});

//Profile Page
$app->group('/profile', function(){
  //Todo: ruta /profile
  echo "Hello";
});

//Dashboard Page
$app->group('/dashboard', function(){
  //Todo: ruta /dashboard
  echo "Hello";
});

//Settings Page
$app->group('/settings', function(){
  //Todo: ruta /settings
  echo "Hello";
});

// -------------------------API

//User
$app->group('/user', function(){
    require __DIR__.'/api_routes/apiUserRoutes.php';
});

//Folder
$app->group('/folder', function (){
    require __DIR__.'/api_routes/apiFolderRoutes.php';
});
