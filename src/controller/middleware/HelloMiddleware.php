<?php
/**
 * Created by PhpStorm.
 * User: rafarebollo
 * Date: 11/4/18
 * Time: 19:31
 */

namespace PWBox\controller\middleware;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class HelloMiddleware
{

    public function __invoke(Request $request, Response $response, callable $next)
    {

        $response->getBody()->write('BEFORE');
        $next($request, $response);
        $response->getBody()->write('AFTER');
        return $response;

    }

}