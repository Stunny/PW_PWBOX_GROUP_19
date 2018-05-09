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

class UseCaseGetFolder
{

    private $repository;

    function __construct(FolderRepository $repository)
    {
        $this->repository = $repository;
    }

    function __invoke(array $rawData)
    {
        $folder = $this->repository->get($rawData['folderID'], $rawData['userID']);

        if($folder == null){
            return [];
        }

        return [
            "folderID" => $folder->getId(),
            "folderCreatorId" => $folder->getCreador(),
            "folderName" => $folder->getNom(),
            "path" => $folder->getPath(),
            "created_at" => $folder->getCreatedAt(),
            "updated_at" => $folder->getUpdatedAt()
        ];
    }
}