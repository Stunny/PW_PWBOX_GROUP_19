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

//----------------------------------------------------------------------------------User
$container['user-repository'] = function ($container){
    $repository = new \PWBox\model\repositories\impl\DoctrineUserRepository(
        $container->get('doctrine')
    );
    return $repository;
};

$container['post-user-service'] = function ($container){
    $service = new \PWBox\model\use_cases\UserUseCases\UseCasePostUser(
        $container->get('user-repository')
    );
    return $service;
};

$container['get-user-service'] = function ($container){
    $service = new \PWBox\model\use_cases\UserUseCases\UseCaseGetUser(
        $container->get('user-repository')
    );
    return $service;
};

$container['put-user-service'] = function ($container){
    $service = new \PWBox\model\use_cases\UserUseCases\UseCaseUpdateUser(
        $container->get('user-repository')
    );
    return $service;
};

$container['delete-user-service'] = function ($container){
    $service = new \PWBox\model\use_cases\UserUseCases\UseCaseDeleteUser(
        $container->get('user-repository')
    );
    return $service;
};

//----------------------------------------------------------------------------------Folder
$container['folder-repository'] = function ($container){
    $repository = new \PWBox\model\repositories\impl\DoctrineFolderRepository(
        $container->get('doctrine')
    );
    return $repository;
};

$container['post-folder-service'] = function ($container){
    $service = new \PWBox\model\use_cases\FolderUseCases\UseCasePostFolder(
        $container->get('folder-repository'),
        $container->get('user-repository')
    );
    return $service;
};

$container['get-folder-service'] = function ($container){
    $service = new \PWBox\model\use_cases\FolderUseCases\UseCaseGetFolder(
        $container->get('folder-repository')
    );
    return $service;
};

$container['put-folder-service'] = function ($container){
    $service = new \PWBox\model\use_cases\FolderUseCases\UseCaseUpdateFolder(
        $container->get('folder-repository')
    );
    return $service;
};

$container['delete-folder-service'] = function ($container){
    $service = new \PWBox\model\use_cases\FolderUseCases\UseCaseDeleteFolder(
        $container->get('folder-repository')
    );
    return $service;
};
