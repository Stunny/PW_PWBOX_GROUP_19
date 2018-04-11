<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/11/2018
 * Time: 11:00 AM
 */

namespace PWBox\model\repositories\impl;


use PWBox\model\User;
use PWBox\model\UserRepository;

class DoctrineUserRepository implements UserRepository
{

    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user)
    {
        // TODO: Implement save() method.
    }

    public function delete(User $user)
    {
        // TODO: Implement delete() method.
    }
}