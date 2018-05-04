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

    public function __invoke(array $rawData, array $args): bool
    {
        var_dump($rawData);
        var_dump($args);
        $file = $this->repository->getData(new File($args['fileID'], null, null, $args['folderID'], null, null, null));
        //$file = $this->repository->getData($args['userID'], $args['folderID'], $args['fileID'], $rawData['filename']);
        if($file != null){
            $this->repository->updateData(new File(
                $fileId = $file->getId(),
                isset($rawData['filename'])? $rawData['filename']: $file->getName(),
                $file->getCreador(),
                isset($rawData['folder'])? $rawData['folder']: $file->getFolder(),
                $file->getCreatedAt(),
                $file->getUpdatedAt(),
                null
            ), $args['userID']);

            return true;
        }else{
            return false;
        }
    }

}