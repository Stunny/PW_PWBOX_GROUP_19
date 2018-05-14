<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/15/2018
 * Time: 12:57 PM
 */

namespace PWBox\model\use_cases\FileUseCases;


use PWBox\model\File;
use PWBox\model\repositories\FileRepository;
use Slim\Http\UploadedFile;

class UseCaseUploadFile
{
    private $repository;

    /**
     * UseCasePostUser constructor.
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $uploadedFiles, int $userID, int $folderID)
    {
        $qFiles = count($uploadedFiles);
        for($i = 0; $i < $qFiles; $i++){
            //todo: subir los archivos a la carpeta correspondiente 
        }

        $now = new \DateTime('now');

    }
}