<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:04 PM
 */

namespace PWBox\model\repositories;

use PWBox\model\User as User;


interface UserRepository
{
    public function save(User $user): User;

    public function delete(int $userId);

    public function get(int $id): User;

    public function update(User $user): User;
}
