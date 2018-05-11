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
    public function save(User $user);

    public function delete($userId);

    public function get($userId);

    public function update(User $user);

    public function changePassword($userId, $oldPassword, $newPassword);

    public function verify($verificationHash);

    public function login($userEmail, $userPassword);

    public function getRootFolderId($userId);
}
