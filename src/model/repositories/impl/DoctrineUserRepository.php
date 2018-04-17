<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/11/2018
 * Time: 11:00 AM
 */

namespace PWBox\model\repositories\impl;


use Doctrine\DBAL\Connection;
use PWBox\model\User;
use PWBox\model\repositories\UserRepository;


class DoctrineUserRepository implements UserRepository
{

    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private const INSERT_QUERY = 'INSERT INTO `user`(`username`, `email`, `birthdate`, `password`, `created_at`, `updated_at`) VALUES(:username, :email, :birthdate, MD5(:password), :created_at, :updated_at)';
    private const SELECT_QUERY = 'SELECT * FROM `user` WHERE (`id` = :id)';
    private const UPDATE_QUERY = 'UPDATE `user` SET `username` = :username, `email` = :email, `birthdate` = :birthdate, `password` = :password WHERE `id` = :id';
    private const DELETE_QUERY = 'DELETE FROM `user` WHERE (`id` = :id)';
    private const VERIFY_QUERY = 'update user set verified = true where id = :id';

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user)
    {
        $sql = self::INSERT_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("birthdate", $user->getBirthDate(), 'date');
        $stmt->bindValue("password", $user->getPassword(), 'string');
        $stmt->bindValue("created_at", $user->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $user->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();
    }

    public function delete(int $userId)
    {
        $sql = self::DELETE_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $userId, 'integer');
        $stmt->execute();
    }

    public function get(int $id){
        $sql = self::SELECT_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $id, 'integer');
        $stmt->execute();

        $user =  $stmt->fetch();
        if(isset($user['id'])){
            unset($user['id']);
        }
        return $user;
    }

    public function update(User $user){

        $sql = self::UPDATE_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("birthdate", $user->getBirthDate(), 'date');
        $stmt->bindValue("password", $user->getPassword(), 'string');
        $stmt->bindValue("id", $user->getId(), 'integer');
        $stmt->execute();

    }

    public function verify(User $user){

        $sql = self::VERIFY_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $user->getId(), 'integer');
        $stmt->execute();

    }

}
