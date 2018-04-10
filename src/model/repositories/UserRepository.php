<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:04 PM
 */

namespace PWBox\model;


interface UserRepository
{
    public function save(User $user);

    public function delete(User $user);
}