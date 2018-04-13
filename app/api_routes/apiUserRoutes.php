<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 11:36 PM
 */

$this->post('', 'PWBox\controller\UserControllers\PostUserController')->setName('post-user');

$this->get('/{id}', 'PWBox\controller\UserControllers\GetUserController')->setName('get-user');

$this->put('/{id}', 'PWBox\controller\UserControllers\UpdateUserController')->setName('update-user');

$this->delete('/{id}', 'PWBox\controller\UserControllers\DeleteUserController')->setName('delete-user');
