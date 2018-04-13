<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 11:10 AM
 */

namespace PWBox\model\use_cases;

use PWBox\model\User;
use PWBox\model\repositories\UserRepository;

class UseCaseGettUser
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

    public function __invoke($userId): array
    {
        return  $this->repository->get($userId);
    }
}
