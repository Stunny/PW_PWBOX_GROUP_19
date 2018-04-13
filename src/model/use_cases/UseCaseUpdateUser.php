<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 11:34 AM
 */

namespace PWBox\model\use_cases;

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
        //todo: invocacion del caso de uso de actualizar usuario
    }
}
