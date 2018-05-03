<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/5/18
 * Time: 10:13
 */

namespace PWBox\controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

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

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function put(Request $request, Response $response, $args){
        //todo: validacion de datos de usuario
        try{
            $service = $this->container->get('put-user-service');
            $data = $request->getParsedBody();
            $result = $service($data, $args['userID']);

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
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
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
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
        return $response;

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
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
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
        return $response;
    }

}