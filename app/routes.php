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
$dependencies = require __DIR__.'/dependencies.php';

//--------------------------------------SECURITY----------------------------------------------------------------------//
// Configuramos la seguridad de las rutas mediante Json Web Tokens

/*
$app->add(new Tuupola\Middleware\JwtAuthentication(
    [
        "path" => ["/"],
        "secret" => getenv("JWT_SECRET")
    ]
));*/

//--------------------------------------ROUTES------------------------------------------------------------------------//

//--------------------------------------GET
$app->get('/', function(){
    echo "Hello World";
});


//--------------------------------------POST


//--------------------------------------PUT


//--------------------------------------DELETE