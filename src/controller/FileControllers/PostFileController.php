<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/15/2018
 * Time: 5:51 PM
 */

namespace PWBox\controller\FileControllers;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class PostFileController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        try{
            $data = $request->getParsedBody();

            // handle single input with single file upload
            $service = $this->container->get('upload-file-service');
            $fileId = $service($data['name'], $args['userID'], $args['folderID']);
            if (isset($fileId)){
                $response = $response->withStatus(200)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"Uploaded Successsdfully", "res"=>["id"=>$fileId]]));
            }else{
                echo "Bruh, shit went wrong (this msg is an echo)";
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