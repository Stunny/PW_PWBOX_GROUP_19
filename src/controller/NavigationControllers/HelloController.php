<?php
/**
 * Created by PhpStorm.
 * User: rafarebollo
 * Date: 11/4/18
 * Time: 19:02
 */

namespace PWBox\controller\NavigationControllers;


use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Dflydev\FigCookies\FigRequestCookies;

class HelloController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {

        if(isset($_SESSION['counter'])){
            $_SESSION['counter'] += 1;
        }else{
            $_SESSION['counter'] = 1;
        }

        $cookie = FigRequestCookies::get($request, 'advice', 0);

        if(empty($cookie->getValue())){
            $response = FigResponseCookies::set($response, SetCookie::create('advice')
            ->withValue(1)
            ->withDomain('pwbox.test')
            ->withPath('/'));
        }

        $name = $args['name'];
        return $this->container->get('view')
            ->render($response, 'hello.twig', [
                'name' => $name,
                'times'=>$_SESSION['counter'],
                'advice' => $cookie->getValue()
            ]);
    }


}