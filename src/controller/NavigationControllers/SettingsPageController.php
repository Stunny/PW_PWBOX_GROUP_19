<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17/4/18
 * Time: 18:54
 */

namespace PWBox\controller\NavigationControllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class SettingsPageController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        echo "Welcome to the rice fields";
    }
}