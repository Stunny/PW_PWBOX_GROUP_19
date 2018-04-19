<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/15/2018
 * Time: 12:57 PM
 */

namespace PWBox\model\use_cases\FileUseCases;

use PWBox\model\repositories\FileRepository;

class UseCaseGetFile
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
        echo "lost";
        if($file != null){
            return [
                'name' => $file->getName(),
                'creador' => $file->getCreador(),
                'folder' => $file->getFolder(),
                'created_at' => $file->getCreatedAt(),
                'updated_at' => $file->getUpdatedAt()
            ];
        }

        return [];
    }
}