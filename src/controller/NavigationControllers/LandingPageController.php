<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17/4/18
 * Time: 18:38
 */

namespace PWBox\controller\NavigationControllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;


class LandingPageController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {

        if($request->getUri()->getPath() != "/landing" && isset($_SESSION['user'])){
            $response = $response->withStatus(302)
                ->withHeader('location', '/dashboard');
            return $response;
        }

        $messages = $this->container->get('flash')->getMessages();

        //$userRegisteredMessages = isset($messages['user_register'])? $messages['user_register']: [];

        $this->container->get('view')->render($response, 'landing.twig');
    }
}