<?php
/**
 * Created by PhpStorm.
 * User: angel
 * Date: 22/06/2018
 * Time: 13:18
 */

namespace PWBox\model\use_cases\UserUseCases;

use PWBox\model\repositories\UserRepository;

class UseCaseChangeMail
{

    private $repository;

    /**
     * UseCaseChangeMail constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    function __invoke(array $rawData, $userId)
    {
        // TODO: Implement __invoke() method.
        $user = $this->repository->get($userId);
        if(isset($user['username'])) {
            $result = $this->repository->changeMail($userId, $rawData['mail']);
            return $result;
        }
        return 404;
    }

}