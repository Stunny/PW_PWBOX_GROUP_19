<?php

namespace PWBox\controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

/**
 *
 */
class FileController
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
            $service = $this->container->get('get-file-service');
            $fileData = $service($args['id']);

            if(!isset($fileData['name'])){
                $response = $response
                    ->withStatus(404)
                    ->withHeader('Content-type', 'text/html')
                    ->write('File not found');
            }else{
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-type', 'application/json')
                    ->write(json_encode($fileData));
            }

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'text/html')
                ->write('Something went wrong<br>'.$e->getMessage());
        } catch (NotFoundExceptionInterface $e) {
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'text/html')
                ->write('Something went wrong<br>'.$e->getMessage());
        } catch (ContainerExceptionInterface $e) {
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'text/html')
                ->write('Something went wrong<br>'.$e->getMessage());
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
            $service = $this->container->get('put-file-service');
            $data = $request->getParsedBody();
            $result = $service($data, $args['id']);

            if($result){
                $response = $response
                    ->withStatus(200)
                    ->withHeader('Content-type', 'text/html')
                    ->write('Updated successfully');
            }else{
                $response = $response
                    ->withStatus(404)
                    ->withHeader('Content-type', 'text/html')
                    ->write('File not found');
            }

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'text/html')
                ->write('Something went wrong<br>'.$e->getMessage());
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
    public function delete(Request $request, Response $response, $args){
        try{
            $service = $this->container->get('delete-file-service');
            $service($args['id']);

            //todo: confirmacion de eliminacion del archivo correcta

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'text/html')
                ->write('Something went wrong'.'<br>'.$e->getMessage());
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
    public function upload(Request $request, Response $response, $args){
        try{
            $uploadedFiles = $request->getUploadedFiles();
            $data = $request->getParsedBody();

            // handle single input with single file upload
            $uploadedFile = $uploadedFiles[$data['filename']];
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $service = $this->container->get('upload-file-service');
                $service($uploadedFile, $data);
            }

            //todo: confirmacion de subida del archivo correcta

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'text/html')
                ->write('Something went wrong'.'<br>'.$e->getMessage());
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
    public function download(Request $request, Response $response, $args){
        try {
            $service = $this->container->get('download-file-service');
            $file = $service($args['id']);

            $response = $response
                ->withHeader('Content-Description', 'File Transfer')
                ->withHeader('Content-Type', 'application/octet-stream')
                ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
                ->withHeader('Expires', '0')
                ->withHeader('Cache-Control', 'must-revalidate')
                ->withHeader('Pragma', 'public')
                ->withHeader('Content-Length', filesize($file));

            readfile($file);

        } catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'text/html')
                ->write('Something went wrong'.'<br>'.$e->getMessage());
        }catch (NotFoundExceptionInterface $e) {
            echo $e->getMessage();
        } catch (ContainerExceptionInterface $e) {
            echo $e->getMessage();
        }

        return $response;
    }
}
