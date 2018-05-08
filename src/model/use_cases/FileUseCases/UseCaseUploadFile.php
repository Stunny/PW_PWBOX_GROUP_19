<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/15/2018
 * Time: 12:57 PM
 */

namespace PWBox\model\use_cases\FileUseCases;


use PWBox\model\File;
use PWBox\model\repositories\FileRepository;

class UseCaseUploadFile
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

    public function __invoke($uploadedFileName, int $userID, int $folderID)
    {
        $now = new \DateTime('now');
        $fileObject = new File(
            null,
            $uploadedFileName,
            $userID,
            $folderID,
            $now,
            $now,
            $uploadedFileName
        );

        $fileObject = $this->repository->post($fileObject);

        return $fileObject->getId();
    }
}