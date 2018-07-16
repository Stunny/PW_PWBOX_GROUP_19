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
        if ($this->folderRepo->get(intval($folderId), intval($userId))->getNom() != null){


            $currentFolderPath = $this->folderRepo->get($folderId, $userId)->getPath();

            $currentFolderCreatorId = $this->folderRepo->getByPath($currentFolderPath)['creador'];

            $content = scandir(self::FOLDERS_DIR . $currentFolderPath);

            foreach ($content as $item){
                $aux = array();

                if($item == "." || $item == "..")
                    continue;

                $isDir = is_dir(self::FOLDERS_DIR . $currentFolderPath . '/' . $item);

                if($isDir){
                    $folder =  $this->folderRepo->getByName($item, $currentFolderCreatorId);

                    $aux['id'] = $folder->getId();
                    $aux['type'] = 'folder';
                }else {
                    $file = $this->fileRepo->getFileByName($item, $folderId);

                    $aux['id'] = $file['id'];
                    $aux['type'] = 'file';
                }

                $aux['filename'] = $item;

                array_push($contentArray, $aux);
            }

            return $contentArray;
        }else{
            return 404;
        }
    }

}