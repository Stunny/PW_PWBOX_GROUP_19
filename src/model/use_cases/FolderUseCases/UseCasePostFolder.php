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
use PWBox\model\repositories\UserRepository;
use PWBox\model\User;


class UseCasePostFolder
{
    private $repository;

    private $userRepository;

    /**
     * UseCasePostUser constructor.
     * @param FolderRepository|UserRepository $repository
     * @param UserRepository $userRepository
     */
    public function __construct(FolderRepository $repository, UserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(array $rawData, $userID)
    {
        $user = $this->userRepository->get($userID);

        if(!isset($user['username'])){
            return 403;
        }else{
            $now = new \DateTime('now');
            $folder = new Folder(
                null,
                $userID,
                $rawData['folderName'],
                $rawData['parent'],
                $now,
                $now
            );

            return $this->repository->create($userID, $folder);

        }
    }
}