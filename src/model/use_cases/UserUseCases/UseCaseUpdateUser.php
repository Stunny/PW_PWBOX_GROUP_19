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

    public function __invoke(array $rawData, $userId)
    {
        $user = $this->repository->get($userId);

        if($user != null){

            $this->repository->update(
                new User(
                    $userId,
                    isset($rawData['username'])? $rawData['username']: $user->getUserName(),
                    isset($rawData['password'])? $rawData['password']: $user->getPassword(),
                    isset($rawData['email'])? $rawData['email']: $user->getEmail(),
                    isset($rawData['imgpath'])? $rawData['imgpath']: $user->getEmail(),
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
