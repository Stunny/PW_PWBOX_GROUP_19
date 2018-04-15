<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/15/2018
 * Time: 12:57 PM
 */

namespace PWBox\model\use_cases\FileUseCases;

use PWBox\model\repositories\FileRepository;

class UseCaseDownloadFile
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

    public function __invoke($fileId)
    {
        $file = $this->repository->getData($fileId);

        if($file != null){
            $content = $this->repository->download($file)->getFile();
            return $content;
        }else{
            return null;
        }
    }
}