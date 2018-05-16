<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 4/5/18
 * Time: 13:34
 */

namespace PWBox\model\use_cases;

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

    public function __invoke($login, $password)
    {
        $result = $this->repository->login($login, $password);


        if (!is_null($result)){
            $rootFolderId = $this->repository->getRootFolderId($result);

            setcookie('rootFolderId', $rootFolderId, time()+86400);
            setcookie('user', $result, time()+86400);

            $_SESSION['user'] = $result;
            session_set_cookie_params(86400);//1 dia entero de sesion
            return 200;
        }else{
            return 202;
        }
    }
}