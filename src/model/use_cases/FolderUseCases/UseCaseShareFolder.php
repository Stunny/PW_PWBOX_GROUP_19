<?php
/**
 * Created by PhpStorm.
 * User: angel
 * Date: 17/05/2018
 * Time: 16:02
 */

namespace PWBox\model\use_cases\FolderUseCases;


use PWBox\model\repositories\FolderRepository;

class UseCaseShareFolder
{
    private $repository;
    function __construct(FolderRepository $repository)
    {
        $this->repository = $repository;
    }

    function __invoke(int $folderID, int $userID, $email, $role)
    {
        return $this->repository->shareFolder($folderID, $userID, $email, $role);

    }
}