<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 11:40 AM
 */

namespace PWBox\controller\UserControllers;


use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;


class UpdateUserController
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
            $service = $this->container->get('update-user-service');
            $data = $request->getParsedBody();
            $service($data, $args['id']);

            //todo: confirmacion de actualizacion del usuario correcta

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'text/html')
                ->write('Something went wrong');
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
        return $response;
    }
}
