<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/5/18
 * Time: 10:13
 */

namespace PWBox\controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class UserController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function get(Request $request, Response $response, $args){
        try{
            $service = $this->container->get('get-user-service');
            $userData = $service($args['userID']);

            if(!isset($userData['username'])){
                $response = $response
                    ->withStatus(404)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"User not found", "res"=>[]]));
            }else{
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"Success", "res"=>$userData]));
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

    /**
     * @param Request $request
     * @param Response $response
     * @return Response|static
     */
    public function post(Request $request, Response $response){
        try{
            $data = $request->getParsedBody();

            if(empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['repeat-password']) || empty($data['birthdate'])){

                $this->container->get('flash')->addMessage('error', 'Credentials shouldn|\'t be empty to register');
                return $response->withStatus(401)->withHeader('location', '/register');
            }

            if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){

                $this->container->get('flash')->addMessage('error', 'Please enter a valid email');
                return $response->withStatus(401)->withHeader('location', '/register');
            }

            if(strlen($data['username'])>20){
                $this->container->get('flash')->addMessage('error', 'User name too long');
                return $response->withStatus(401)->withHeader('location', '/register');
            }

            if(strcmp($data['password'], $data['repeat-password']) != 0){
                $this->container->get('flash')->addMessage('error', 'Passwords don\'t match');
                return $response->withStatus(401)->withHeader('location', '/register');
            }


            $service = $this->container->get('post-user-service');
            $result = $service($data, $request->getUploadedFiles(), $this->container->get('generate-verification-service'), $this->container->get('profile-img-service'));

            if($result == 409){
                $response = $response->withStatus(302)->withHeader('location', '/register');
                $this->container->get('flash')->addMessage('error', 'Username or email already exist');

            }else {
                $this->container->get('flash')->addMessage('user_register', 'User registered successfully');
                $response = $response
                    ->withStatus(302)
                    ->withHeader('location', '/login');
            }

        }catch (\Exception $e){
            $response = $response->withStatus(500);
            $this->container->get('view')->render($response, 'register.twig', ["form" => "Register", "error"=>["Something went wrong. Try again later."]]);
        }
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function put(Request $request, Response $response, $args){
        //todo: implementacion de la obtencion de los datos metodo PUT
        try{
            $service = $this->container->get('put-user-service');
            $rawData = $request->getParsedBody();
            $result = $service($rawData, $args['userID']);
            if($result){
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>'Updated successfully', "res"=>[]]));
            }else{
                $response = $response
                    ->withStatus(404)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"User not found", "res"=>[]]));
            }

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>'Something went wrong: '.$e->getMessage(), "res"=>[]]));
        }
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function delete(Request $request, Response $response, $args){
        try{
            $service = $this->container->get('delete-user-service');
            $result = $service($args['userID']);

            if($result){
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>'Deleted successfully', "res"=>[]]));
            }else{
                $response = $response
                    ->withStatus(404)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"User not found", "res"=>[]]));
            }


        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>'Something went wrong: '.$e->getMessage(), "res"=>[]]));
        }
        return $response;

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function verifyEmail(Request $request, Response $response, $args){
        try{
            $service = $this->container->get('verify-user-service');
            $service($args['hash']);
        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'text/html')
                ->write('Something went wrong'.'<br>'.$e->getMessage());
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function changePassword(Request $request, Response $response, $args){
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
        }
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response|static
     */
    public function login(Request $request, Response $response, $args){

        try{
            $service = $this->container->get('user-login');
            $data = $request->getParsedBody();

            if(empty($data['login']) || empty($data['password'])){

                $this->container->get('view')->render($response, 'login.twig', ["form" => "Login", "error"=>["Credentials shouldn't be empty."]]);
                return $response->withStatus(401);
            }

            $result = $service($data['login'], $data['password']);

            if($result == 200){
                $response = $response
                    ->withStatus(200)
                    ->withHeader('location', '/dashboard');
            }else{
                $this->container->get('view')->render($response, 'login.twig', ["form" => "Login", "error"=>["Wrong email or password"]]);
            }

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>'Something went wrong: '.$e->getMessage(), "res"=>[]]));
        }

        return $response;

    }

    public function logout(Request $request, Response $response){
        try{
            $service = $this->container->get('user-logout');
            $service();

            $response = $response->withStatus(302)
                ->withHeader('location', '/');

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>'Something went wrong: '.$e->getMessage(), "res"=>[]]));
        }

        return $response;
    }

    public function changeProfileImage(Request $request, Response $response, $args){
        try{
            $service = $this->container->get('profile-img-service');
            $service($request->getUploadedFiles()['file'], $this->container->get('user-repository')->get($args['userID'])['username']);

            $response = $response->withStatus(302)
                ->withHeader('location', '/');

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>'Something went wrong: '.$e->getMessage(), "res"=>[]]));
        }

        return $response;
    }

}