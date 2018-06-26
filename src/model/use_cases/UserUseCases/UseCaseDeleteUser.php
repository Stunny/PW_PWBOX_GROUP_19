<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 11:35 AM
 */

namespace PWBox\model\use_cases\UserUseCases;

use PWBox\model\User;
use PWBox\model\repositories\UserRepository;

class UseCaseDeleteUser
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

    public function __invoke($user)
    {

        if($user != null){
            $this->repository->delete($user);
            return true;
        }else{
            return false;
        }
    }
}
