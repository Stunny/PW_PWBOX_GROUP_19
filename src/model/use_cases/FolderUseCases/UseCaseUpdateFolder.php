<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 3:33 PM
 */

namespace PWBox\model\use_cases\FolderUseCases;


use PWBox\model\Folder;
use PWBox\model\repositories\FolderRepository;

class UseCaseUpdateFolder
{

    private $repository;
    function __construct(FolderRepository $repository)
    {
        $this->repository = $repository;
    }

    function __invoke(array $rawData, int $folderID, int $userID)
    {
        $response = $this->repository->update(new Folder($folderID, null, $rawData['foldername'], null, null, null), $userID);
        if ($response){
            //rename folder y todos aquellos path que contengan ese folder -_-
            $path = $this->repository->get($folderID, $userID)->getPath();
            //rename("/home/vagrant/pwbox/appdata/user_folders/" . $path . DIRECTORY_SEPARATOR . $file->getName(), "/home/vagrant/pwbox/appdata/user_folders/" . $path . DIRECTORY_SEPARATOR . $return->getName());
            return true;
        }else{
            return false;
        }
    }
}