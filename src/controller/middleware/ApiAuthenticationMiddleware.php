<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2/5/18
 * Time: 11:51
 */

namespace PWBox\controller\middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ApiAuthenticationMiddleware
{

    public function __construct()
    {

    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        if(!isset($_SESSION['user'])){
            $response = $response
                ->withStatus(403)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>"Unauthorised. Please Login", "res"=>[]]));

            return $response;
        }

        return $next($request, $response);
    }

}