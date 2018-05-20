<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 11:10 AM
 */

namespace PWBox\model\use_cases\UserUseCases;

use PWBox\model\User;
use PWBox\model\repositories\UserRepository;

class UseCaseGetUser
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

    public function __invoke($userId)
    {
        $user = $this->repository->get($userId);

        if($user == null){
            return [];
        }

        return [
            "userID" => $user['id'],
            "username" => $user['username'],
            "email" => $user['email'],
            "created_at" => $user['created_at'],
            "birthdate" => $user['birthdate']
        ];
    }
}
