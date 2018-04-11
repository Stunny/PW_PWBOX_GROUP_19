<?php
/**
 * Created by PhpStorm.
 * User: avoge
 * Date: 05/04/2018
 * Time: 10:53
 */

$container = $app->getContainer();

//------------------------------MIDDLEWARE----------------------------------------------------------------------------//

//slim/twig
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../src/view/templates', []);
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};

//doctrine/dbal
$container['doctrine'] = function ($container) {
    $config = new \Doctrine\DBAL\Configuration();
    $conn = \Doctrine\DBAL\DriverManager::getConnection(
        $container->get('settings')['database'],
        $config
    );
    return $conn;
};

//====================================================================================================================//
//------------------------------MODEL DEPENDENCIES--------------------------------------------------------------------//

//User
$container['user-repository'] = function ($container){
    $repository = new \PWBox\model\repositories\impl\DoctrineUserRepository(
        $container->get('doctrine')
    );
    return $repository;
};

$container['post-user-service'] = function ($container){
    $service = new \PWBox\model\use_cases\UseCasePostUser(
        $container->get('user-repository')
    );
    return $service;
};

//Folder