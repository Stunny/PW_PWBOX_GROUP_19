<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/5/18
 * Time: 10:23
 */

namespace PWBox\controller;

use function FastRoute\cachedDispatcher;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use PWBox\model\Folder;

class FolderController
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
            $service = $this->container->get('get-folder-service');
            $folderData = $service($args);

            if(!isset($folderData)){
                $response = $response
                    ->withStatus(404)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"Folder not found", "res"=>[]]));
            }else{
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"Success", "res"=> $folderData]));
            }

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>'Something went wrong: '.$e->getMessage(), "res"=>[]]));
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {}
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function getTree(Request $request, Response $response, $args){
        try{
            $service = $this->container->get('folder-tree-service');
            $result = $service($args);

            if($result != null) {
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg" => "Success", "res" => $result]));
            }else{
                $response = $response
                    ->withStatus(404)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"Folder not found", "res"=>[]]));
            }

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>'Something went wrong: '.$e->getMessage(), "res"=>[]]));
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {}
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function getContent(Request $request, Response $response, $args){
        try{
            $service = $this->container->get('folder-content-service');
            $result = $service($args);

            if($result != null) {
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg" => "Success", "res" => $result]));
            }else{
                $response = $response
                    ->withStatus(404)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"Folder not found", "res"=>[]]));
            }
        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>'Something went wrong: '.$e->getMessage(), "res"=>[]]));
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {}
        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function post(Request $request, Response $response, $args){
        //todo: validacion datos de folder
        try {
            $userID = $args['userID'];
            $rawData = $request->getParsedBody();
            $service = $this->container->get('post-folder-service');
            $result = $service($rawData, $userID);

            $response = $response
                ->withStatus($result)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>$result, "res"=>[]]));
        } catch (\Exception $e) {
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>'Something went wrong: '.$e->getMessage(), "res"=>[]]));
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
        //todo: validacion de datos de fichero
        try{
            $service = $this->container->get('put-folder-service');
            $data = $request->getParsedBody();
            $result = $service($data, $args['folderID'], $args['userID']);

            if($result){
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>'Updated successfully', "res"=>[]]));
            }else{
                $response = $response
                    ->withStatus(404)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"Folder not found", "res"=>[]]));
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

            //todo: control de carpeta vacia

            $service = $this->container->get('delete-folder-service');
            $result = $service($args['folderID'], $args['userID']);
            if($result){
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>'Deleted successfully', "res"=>[]]));
            }else{
                $response = $response
                    ->withStatus(404)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"Folder not found", "res"=>[]]));
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