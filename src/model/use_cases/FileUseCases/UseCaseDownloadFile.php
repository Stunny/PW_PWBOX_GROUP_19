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

class UseCaseDownloadFile
{
    private $fileRepository;
    private $folderRepository;

    /**
     * UseCasePostUser constructor.
     * @param FileRepository $repository
     * @param FolderRepository $folderRepository
     */
    public function __construct(FileRepository $repository, FolderRepository $folderRepository)
    {
        $this->fileRepository = $repository;
        $this->folderRepository = $folderRepository;
    }

    public function __invoke(File $fileInfo)
    {
        //si se puede acceder a la carpeta (porque bien sea propietario o se la hayan compartido) poner el id del creador correcto, else dejar id incorrecto y no hara la descarga
        if($this->folderRepository->get($fileInfo->getFolder(), $fileInfo->getCreador())->getNom() != null){
            $fileInfo->setCreador($this->folderRepository->get($fileInfo->getFolder(), $fileInfo->getId())->getCreador());
        }
        $file = $this->fileRepository->getData($fileInfo);

        if($file->getFolder() != null){
            $content = $this->fileRepository->download($file, $this->folderRepository->get($file->getFolder(), $file->getCreador()))->getFile();
            return $content;
        }else{
            var_dump($file);
            die();
            return false;
        }
    }
}