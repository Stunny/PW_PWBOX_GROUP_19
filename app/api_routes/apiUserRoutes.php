<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 11:36 PM
 */

$this->post('/', 'PWBox\controller\PostUserController')->setName('post-user');

$this->get('/{id}', 'PWBox\controller\GetUserController')->setName('get-user');

$this->put('/{id}', 'PWBox\controller\UpdateUserController')->setName('update-user');

$this->delete('/{id}', 'PWBox\controller\DeleteUserController')->setName('delete-user');
