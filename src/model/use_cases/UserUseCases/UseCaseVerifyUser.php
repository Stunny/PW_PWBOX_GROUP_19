<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 17/4/18
 * Time: 12:39
 */

namespace PWBox\model\use_cases\UserUseCases;


use PWBox\model\repositories\UserRepository;

class UseCaseVerifyUser
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

    public function __invoke($verificationHash)
    {
        $this->repository->verify($verificationHash);
    }
}