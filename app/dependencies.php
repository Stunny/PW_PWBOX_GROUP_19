<?php
/**
 * Created by PhpStorm.
 * User: avoge
 * Date: 05/04/2018
 * Time: 10:53
 */

$container = [];

//------------------------------CONTROLLERS---------------------------------------------------------------------------//

//------------------------------MIDDLEWARE----------------------------------------------------------------------------//

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__.'/../src/View/templates', [
        'cache' => __DIR__.'/../var/cache'
    ]);

    $basePath =
        rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};
return $container;