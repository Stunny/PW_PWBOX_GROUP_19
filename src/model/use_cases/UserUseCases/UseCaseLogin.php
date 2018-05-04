<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 4/5/18
 * Time: 13:34
 */

namespace PWBox\model\use_cases\UserUseCases;

use PWBox\model\User;
use PWBox\model\repositories\UserRepository;

class UseCaseLogin
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

    public function __invoke($email, $password)
    {
        $result = $this->repository->login($email, $password);

        if (!is_null($result)){
            $_SESSION['user'] = $email;
            session_set_cookie_params(86400);//1 dia entero de sesion
            return 200;
        }else{
            return 202;
        }
    }
}