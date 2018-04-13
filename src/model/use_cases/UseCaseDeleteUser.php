<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 11:35 AM
 */

namespace PWBox\model\use_cases;

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

    public function __invoke($userId)
    {
        $this->repository->delete($userId);
        //todo: retorno de la invocacion del caso de uso de eliminar usuario
    }
}
