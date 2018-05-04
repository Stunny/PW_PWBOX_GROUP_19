<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:16 PM
 */

namespace PWBox\model\use_cases\UserUseCases;

use PWBox\model\User;
use PWBox\model\repositories\UserRepository;

class UseCasePostUser
{
    private $repository;

    /**
     * UseCasePostUser constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $rawData, $uploadedFiles, $generateVerificationService, $postProfileImgService)
    {

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

        $generateVerificationService($verificationHash, $user->getEmail());

        if(!file_exists("/home/public/pwbox/appdata")){
            mkdir("/home/public/pwbox/appdata", 0777, true);
        }

        if(!file_exists("/home/public/pwbox/appdata/user_folders")){
            mkdir("/home/public/pwbox/appdata/user_folders", 0777, true);

        }

        mkdir("/home/public/pwbox/appdata/user_folders/".$rawData['username']);

    }
}