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
        $folder = new Folder($folderID, null, $rawData['nom'], $rawData['path'], null, null);
        return $this->repository->update($folder, $userID);
    }
}