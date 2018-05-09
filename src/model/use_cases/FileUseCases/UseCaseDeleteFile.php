<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/15/2018
 * Time: 12:58 PM
 */

namespace PWBox\model\use_cases\FileUseCases;

use PWBox\model\File;
use PWBox\model\repositories\FileRepository;

class UseCaseDeleteFile
{
    private $repository;

    /**
     * UseCasePostUser constructor.
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($fileId, $userID, $folderID)
    {
        $file = $this->repository->getData(new File($fileId, null, null, null, null, null, null));

        if($file != null){
            return $this->repository->delete($file, $userID, $folderID);
        }else{
            return false;
        }
    }
}