<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18/4/18
 * Time: 10:30
 */

namespace PWBox\controller\UserControllers;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class ChangePasswordController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        //todo: validacion de datos de usuario
        try{
            $service = $this->container->get('change-password-service');
            $data = $request->getParsedBody();
            $result = $service($data, $args['userID']);

            switch($result){
                case 200:
                    $response = $response
                        ->withStatus(200)
                        ->withHeader('Content-type', 'application/json')
                        ->write(json_encode(["msg"=>'Updated successfully', "res"=>[]]));
                    break;
                case 404:
                    $response = $response
                        ->withStatus(404)
                        ->withHeader('Content-type', 'application/json')
                        ->write(json_encode(["msg"=>"User not found", "res"=>[]]));
                    break;
                default:
                    $response = $response
                        ->withStatus(403)
                        ->withHeader('Content-type', 'application/json')
                        ->write(json_encode(["msg"=>"Forbidden", "res"=>[]]));
            }

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>'Something went wrong: '.$e->getMessage(), "res"=>[]]));
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
        return $response;
    }
}