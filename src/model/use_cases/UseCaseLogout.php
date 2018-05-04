<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 4/5/18
 * Time: 17:08
 */

namespace PWBox\model\use_cases;


class UseCaseLogout
{

    public function __construct()
    {
    }

    public function __invoke()
    {
        if(isset($_SESSION['user'])){
            unset($_SESSION['user']);
        }
    }

}