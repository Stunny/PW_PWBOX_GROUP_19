<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/5/18
 * Time: 18:25
 */

namespace PWBox\controller\NavigationControllers;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class RegisterPageController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $messages = $this->container->get('flash')->getMessages();

        //$userRegisteredMessages = isset($messages['user_register'])? $messages['user_register']: [];

        $this->container->get('view')->render($response, 'register.twig', ["form" => "Register"]);
    }
}