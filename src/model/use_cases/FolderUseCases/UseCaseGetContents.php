<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 6/5/18
 * Time: 16:36
 */

namespace PWBox\model\use_cases\FolderUseCases;

use PWBox\model\File;
use PWBox\model\Folder;
use PWBox\model\repositories\FileRepository;
use PWBox\model\repositories\FolderRepository;

class UseCaseGetContents
{

    private const FOLDERS_DIR = "/home/vagrant/pwbox/appdata/user_folders/";

    private $folderRepo;
    private $fileRepo;

    function __construct(FolderRepository $repository, FileRepository $fileRepository)
    {
        $this->folderRepo = $repository;
        $this->fileRepo = $fileRepository;
    }

    public function __invoke(array $urlArgs)
    {
        $folderId = $urlArgs['folderID'];
        $userId = $urlArgs['userID'];
        $contentArray = array();

        //miramos si la carpeta existe
        if ($this->folderRepo->get($folderId, $userId)->getNom() != null){

            //insertamos ficheros
            $fileIds = $this->fileRepo->getFileId($userId, $folderId);
            foreach ($fileIds as $clave){
                $fileData = $this->fileRepo->getData(new File($clave['id'], null, $userId, $folderId, null, null, null));
                $aux = array();
                /*array_push($aux, $fileData->getId());
                array_push($aux, 'file');
                array_push($aux, $fileData->getName());
                */
                $aux['id'] = $fileData->getId();
                $aux['type'] = 'file';
                $aux['filename'] = $fileData->getName();
                array_push($contentArray, $aux);
            }

            //insertamos carpetas
            $currentFolderPath = $this->folderRepo->get($folderId, $userId)->getPath();
            $currentFolderLength = strlen($currentFolderPath);
            $pathId = $this->folderRepo->getPathAndId($userId);
            foreach ($pathId as $clave){
                if (strlen($clave['path']) > $currentFolderLength){
                    if (substr($clave['path'], $currentFolderLength, strlen($clave['path'])) != null){
                        $folderName = substr($clave['path'], $currentFolderLength + 1, strlen($clave['path']));
                        $folder = $this->folderRepo->getByName($folderName, $userId);
                        $aux = array();
                        /*array_push($aux, $folder->getId());
                        array_push($aux, 'folder');
                        array_push($aux, $folderName);*/
                        $aux['id'] = $folder->getId();
                        $aux['type'] = 'folder';
                        $aux['filename'] = $folderName;

                        array_push($contentArray, $aux);
                    }
                }
            }

            //borramos todas aquellas carpetas que no son las que estan directamente debajo de la seleccionada (campo id = null)
            foreach ($contentArray as $clave => $valor){
                if (strlen($valor['id']) == null){
                    unset($contentArray[$clave]);
                }
            }

            return $contentArray;
        }else{
            return 404;
        }
    }

}