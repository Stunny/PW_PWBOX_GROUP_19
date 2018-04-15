<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 3:33 PM
 */

namespace PWBox\model\use_cases\FolderUseCases;


use PWBox\model\repositories\FolderRepository;

class UseCaseGetFolder
{

    private $repository;

    function __construct(FolderRepository $repository)
    {
        $this->repository = $repository;
    }

    //TODO: RECIVIR EN EL INKOVE EL USUARIO QUE QUIERE
    function __invoke(array $rawData)
    {
        $folderID = $rawData['id'];
        $this->repository->get($folderID);
    }
}