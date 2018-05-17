<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/5/18
 * Time: 10:13
 */

namespace PWBox\controller;

use function FastRoute\cachedDispatcher;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

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
            $rawData = $request->getParsedBody();
            $service = $this->container->get('post-user-service');
            $result = $service($rawData, $request->getUploadedFiles(), $this->container->get('generate-verification-service'), $this->container->get('profile-img-service'));

            if($result == 409){
                $response = $response->withStatus(302)->withHeader('location', '/register');
                $this->container->get('flash')->addMessage('error', 'Username or email already exist');

            }else {
                $msg = "Please click on the following link to verify your account:\nhttp://pwbox.test/someRandomHash";
                $msg = wordwrap($msg,70);
                $to      = $rawData['email'];
                $subject = 'Verification email for PWBOX';
                //TODO: ENVIAR EMAIL DE CONFIRMACION AL USUARIO QUE SE HA REGISTRADO
                //self::sendMail($to, $subject, $msg, $rawData['username']);

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

    private function sendMail($to, $subject, $message, $userName){
        var_dump($to);
        var_dump($subject);
        var_dump($message);
        var_dump($userName);

        $transport = (new Swift_SmtpTransport('smtp.live.com', 587, 'tls'))
            ->setUsername('pw2box@hotmail.com')
            ->setPassword('emailPassword1');
        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
            ->setFrom(['pw2box@hotmail.com' => 'PWBOX confirmation mail'])
            ->setTo([$to, $to => $userName])
            ->setBody($message);

        $result = $mailer->send($message);
        var_dump($result);
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
            $rawData = $request->getParsedBody();
            var_dump($rawData);
            var_dump($args);

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