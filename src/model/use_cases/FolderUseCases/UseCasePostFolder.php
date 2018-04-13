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
     * @param UserRepository $repository
     */
    public function __construct(FolderRepository $repository, UserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(array $rawData)
    {
        $userId = $rawData['creador'];

        $user = $this->userRepository->get($userId);

        if(!isset($user['username'])){
            return false;
        }

        $now = new \DateTime('now');
        $folder = new Folder(
            null,
            $rawData['creador'],
            $rawData['nom'],
            $rawData['path'],
            $now,
            $now
        );

        $this->repository->create($userId, $folder);

        return true;
    }
}