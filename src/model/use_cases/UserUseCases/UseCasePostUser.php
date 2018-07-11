<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:16 PM
 */

namespace PWBox\model\use_cases\UserUseCases;

use PWBox\model\Folder;
use PWBox\model\repositories\FolderRepository;
use PWBox\model\User;
use PWBox\model\repositories\UserRepository;

class UseCasePostUser
{
    private $repository;
    private $folderRepository;

    /**
     * UseCasePostUser constructor.
     * @param UserRepository $repository
     * @param FolderRepository $folderRepository
     */
    public function __construct(UserRepository $repository, FolderRepository $folderRepository)
    {
        $this->repository = $repository;
        $this->folderRepository = $folderRepository;
    }

    public function __invoke(array $rawData, $uploadedFiles, $generateVerificationService, $postProfileImgService)
    {

        if($this->repository->exists($rawData['username'], $rawData['email'])){
            return 409;
        }

        $profileImg = isset($uploadedFiles['profileimg'])?$uploadedFiles['profileimg']: null;

        if (!is_null($profileImg)){
            if ($profileImg->getError() === UPLOAD_ERR_OK) {
                $postProfileImgService($profileImg, $rawData['username']);
            }
        }

        $now = new \DateTime('now');
        $user = new User(
            null,
            $rawData['username'],
            $rawData['password'],
            $rawData['email'],
            $rawData['birthdate'],
            false,
            $now,
            $now
        );

        $verificationHash = $this->repository->save($user);
        $userId = $this->repository->login($user->getEmail(), $user->getPassword());
        $rootFolder = $this->folderRepository
            ->init($userId, new Folder(null, $userId, $user->getUserName(), $user->getUserName(), $now, $now));


        $generateVerificationService($verificationHash, $user->getEmail());

        if(!file_exists("/home/vagrant/pwbox/appdata")){
            mkdir("/home/vagrant/pwbox/appdata", 0777, true);
        }

        if(!file_exists("/home/vagrant/pwbox/appdata/user_folders")){
            mkdir("/home/vagrant/pwbox/appdata/user_folders", 0777, true);
        }

        mkdir("/home/vagrant/pwbox/appdata/user_folders/".$rawData['username']);
        return 200;
    }
}