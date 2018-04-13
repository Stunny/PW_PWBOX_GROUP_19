<?php
/**
 * Created by PhpStorm.
 * User: rafarebollo
 * Date: 11/4/18
 * Time: 19:02
 */

namespace PWBox\NavigationControllers\controller;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class HelloController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $name = $args['name'];
        return $this->container->get('view')->render($response, 'hello.twig', ['name' => $name]);
    }


}