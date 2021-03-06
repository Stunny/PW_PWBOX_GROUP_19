<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/5/18
 * Time: 18:12
 */

namespace PWBox\controller\NavigationControllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;


class LoginPageController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {

        if(isset($_SESSION['user'])){
            $response = $response->withStatus(302)
                ->withHeader('location', '/dashboard');
            return $response;
        }

        $user_register = $this->container->get('flash')->getMessage('user_register');
        $error = $this->container->get('flash')->getMessage('error');

        $this->container->get('view')->render($response, 'login.twig', ["form" => "Login", "user_register"=>$user_register, "error"=>$error]);

    }
}