<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18/4/18
 * Time: 10:27
 */

namespace PWBox\model\use_cases\UserUseCases;

use PWBox\model\User;
use PWBox\model\repositories\UserRepository;

class UseCaseChangePassword
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

        if(isset($user['username'])) {
            $result = $this->repository->changePassword($userId, $rawData['oldpassword'], $rawData['newpassword']);
            return $result? 200: 403;
        }else{
            return 404;
        }
    }
}