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
        $file = $this->repository->getData(new File($args['fileID'], null, null, $args['folderID'], null, null, null));
        if($file->getId() != null){
            $return = $this->repository->updateData($file, $args['userID'], $rawData['filename']);
            var_dump($return);
            var_dump($return->getId());
            if ($return->getId() != null){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}