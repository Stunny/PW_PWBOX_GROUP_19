<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:16 PM
 */

namespace PWBox\model\use_cases;

use PWBox\model\User;
use PWBox\model\UserRepository;

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

    public function __invoke(array $rawData)
    {
        // TODO: Implement __invoke() method.
        $now = new \DateTime('now');
        $user = new User(
            $rawData['username'],
            $rawData['password'],
            $rawData['email'],
            $rawData['imgpath'],
            $now,
            $now
        );

        $this->repository->save($user);
    }
}