<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17/4/18
 * Time: 18:53
 */

namespace PWBox\controller\NavigationControllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class DashboardPageController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        if(!isset($_SESSION['user'])){
            $this->container->get('flash')->addMessage('error', 'Error 403: Please Login before accessing any user page.');
            $response = $response
                ->withStatus(302)
                ->withHeader('location', '/login');
            return $response;
        }

        $this->container->get('view')->render($response, 'dashboard.twig', []);
        return $response;
    }
}