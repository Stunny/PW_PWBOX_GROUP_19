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
use PWBox\model\repositories\FolderRepository;
use Slim\Http\UploadedFile;

class UseCaseUploadFile
{
    private $repository;

    /**
     * UseCasePostUser constructor.
     * @param FileRepository $filefileRepository
     */
    public function __construct(FileRepository $filefileRepository)
    {
        $this->repository = $filefileRepository;
    }

    public function __invoke(array $uploadedFiles, int $userID, int $folderID, $fileName)
    {
        $file = $this->repository->post($userID, $folderID, $fileName);

        $qFiles = count($uploadedFiles);
        for($i = 0; $i < $qFiles; $i++){
            //todo: subir los archivos a la carpeta correspondiente 
        }

        $now = new \DateTime('now');
        return $file;

    }
}