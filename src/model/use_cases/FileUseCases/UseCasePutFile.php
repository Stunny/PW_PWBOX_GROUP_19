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

class UseCasePutFile
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

    public function __invoke(array $rawData, $fileId): bool
    {
        $file = $this->repository->getData($fileId);

        if($file != null){
            $this->repository->updateData(new File(
                $fileId,
                isset($rawData['filename'])? $rawData['filename']: $file->getName(),
                $file->getCreador(),
                isset($rawData['folderId'])? $rawData['folderId']: $file->getFolder(),
                null,
                null,
                null
            ));

            return true;
        }else{
            return false;
        }
    }

}