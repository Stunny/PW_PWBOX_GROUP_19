<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/11/2018
 * Time: 11:58 AM
 */

namespace PWBox\controller\UserControllers;


use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;


class PostUserController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response)
    {
        //todo: validacion de datos de usuario
        try{
            $data = $request->getParsedBody();
            $service = $this->container->get('post-user-service');
            $service($data, $request->getUploadedFiles(), $this->container->get('generate-verification-service'), $this->container->get('profile-img-service'));

            $this->container->get('flash')->addMessage('user_register', 'User registered successfully');
            $response = $response
                ->withStatus(302)
                ->withHeader('location', '/');

        }catch (\Exception $e){
            return $this->container->get('view')
                ->render($response, 'register.twig', [
                    'errors'=> $e->getMessage()
                ]);
        } catch (NotFoundExceptionInterface $e) {
            echo $e->getMessage();
        } catch (ContainerExceptionInterface $e) {
            echo $e->getMessage();

        }
        return $response;
    }
}