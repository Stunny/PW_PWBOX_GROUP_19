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

    function __invoke(array $rawData)
    {
        $folder = new Folder($rawData['id'], $rawData['creador'], $rawData['nom'], $rawData['path'], null, null);

        $this->repository->update($folder);
    }
}