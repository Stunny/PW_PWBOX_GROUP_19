<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 11:03 AM
 */

namespace PWBox\controller\UserControllers;


use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

/**
 *
 */
class GetUserController
{

  protected $container;

  function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function __invoke(Request $request, Response $response, $args)
  {

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

}
