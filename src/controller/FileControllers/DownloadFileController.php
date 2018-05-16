<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/15/2018
 * Time: 5:52 PM
 */

namespace PWBox\controller\FileControllers;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use PWBox\model\File;

class DownloadFileController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        try {
            $service = $this->container->get('download-file-service');
            $downloaded = $service(new File($args['fileID'], null, $args['userID'], $args['folderID'], null, null, null));

            if($downloaded){
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"File downloaded", "res"=>[]]));
            }else{
                $response = $response
                    ->withStatus(404)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode(["msg"=>"File not found", "res"=>[]]));
            }

        } catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(["msg"=>'Something went wrong: '.$e->getMessage(), "res"=>[]]));
        }catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }

        return $response;
    }
}