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

$app->get('/', function(Request $req, Response $res){
    echo "Hello World";
});

// -------------------------NAVIGATION

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
$app->group('/user/{id}', function(){
    require_once __DIR__.'/api_routes/apiUserRoutes.php';
});

//Folder
$app->group('/folder/{id}', function (){
    require_once __DIR__.'/api_routes/apiFolderRoutes.php';
});
