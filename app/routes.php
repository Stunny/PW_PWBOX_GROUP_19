<?php
/**
 * Created by PhpStorm.
 * User: avoge
 * Date: 05/04/2018
 * Time: 10:53
 */

//--------------------------------------SECURITY----------------------------------------------------------------------//


//--------------------------------------ROUTES------------------------------------------------------------------------//
/*
$app->get(
    '/hello/{name}',
    'PWBox\controller\NavigationControllers\HelloController'
)->add('PWBox\controller\middleware\HelloMiddleware');*/

// -------------------------API

//User
$app->group('/api/user', function(){
    require __DIR__.'/api_routes/apiUserRoutes.php';
});


// -------------------------NAVIGATION
$app->group('/', function(){
    require __DIR__.'/nav_routes/navigationRoutes.php';
});
