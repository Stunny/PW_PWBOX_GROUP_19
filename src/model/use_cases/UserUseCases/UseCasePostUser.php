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

        $profileImg = $uploadedFiles['profileimg'];
        $imgPath = "";

        if ($profileImg->getError() === UPLOAD_ERR_OK) {
            $imgPath = $postProfileImgService($profileImg, $rawData['username']);
        }

        $now = new \DateTime('now');
        $user = new User(
            null,
            $rawData['username'],
            $rawData['password'],
            $rawData['email'],
            $rawData['birthdate'],
            $imgPath,
            false,
            $now,
            $now
        );

        $verificationHash = $this->repository->save($user);

        $generateVerificationService($verificationHash, $user->getEmail());

    }
}