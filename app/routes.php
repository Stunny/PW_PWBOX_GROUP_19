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

//--------------------------------------GET
$app->get('/', function(Request $req, Response $res){
    echo "Hello World";
});

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    return $this->view->render($response, 'hello.twig', ['name'=>$name]);
});
//--------------------------------------POST


//--------------------------------------PUT


//--------------------------------------DELETE