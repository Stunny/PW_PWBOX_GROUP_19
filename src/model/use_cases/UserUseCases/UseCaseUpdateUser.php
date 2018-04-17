<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 11:34 AM
 */

namespace PWBox\model\use_cases\UserUseCases;

use PWBox\model\User;
use PWBox\model\repositories\UserRepository;

class UseCaseUpdateUser
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

    public function __invoke(array $rawData, $userId): bool
    {
        $user = $this->repository->get($userId);

        if(isset($user['username'])){

            $this->repository->update(
                new User(
                    $userId,
                    isset($rawData['username'])? $rawData['username']: $user['username'],
                    isset($rawData['password'])? $rawData['password']: $user['password'],
                    isset($rawData['email'])? $rawData['email']: $user['email'],
                    isset($rawData['birthdate'])? $rawData['birthdate']: $user['birthdate'],
                    isset($rawData['imgpath'])? $rawData['imgpath']: $user['imgpath'],
                    $user['verified'],
                    null,
                    null
                )
            );

            return true;
        }else{
            return false;
        }

    }
}
