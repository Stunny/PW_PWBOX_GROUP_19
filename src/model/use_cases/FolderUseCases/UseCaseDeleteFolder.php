<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 3:34 PM
 */

namespace PWBox\model\use_cases\FolderUseCases;

use PWBox\model\repositories\FolderRepository;

class UseCaseDeleteFolder
{
    private $repository;

    function __construct(FolderRepository $repository){
        $this->repository = $repository;
    }

    function __invoke(int $folderID, int $userID){

        return $this->repository->delete($folderID, $userID);
    }
}