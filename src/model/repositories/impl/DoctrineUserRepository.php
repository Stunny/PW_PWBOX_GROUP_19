<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/11/2018
 * Time: 11:00 AM
 */

namespace PWBox\model\repositories\impl;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use PWBox\model\User;
use PWBox\model\repositories\UserRepository;


class DoctrineUserRepository implements UserRepository
{

    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private const INSERT_QUERY = 'INSERT INTO `user`(`username`, `email`, `birthdate`, `password`, `created_at`, `updated_at`, `verificationHash`) VALUES(:username, :email, :birthdate, MD5(:password), :created_at, :updated_at, :hash);';
    private const SELECT_QUERY = 'SELECT * FROM `user` WHERE (`id` = :id);';
    private const UPDATE_QUERY = 'UPDATE `user` SET `username` = :username, `email` = :email, `birthdate` = :birthdate, `password` = :password WHERE `id` = :id;';
    private const DELETE_QUERY = 'DELETE FROM `user` WHERE (`id` = :id);';
    private const VERIFY_QUERY = 'update user set verified = true where verificationHash = :hash;';

    private const CHECK_PASS_QUERY = 'select count(*) as count from user where id = :id and password = md5(:password);';
    private const CHANGE_PASS_QUERY = 'update user set password = md5(:password) where id = :id;';

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user)
    {
        $verificationHash = md5(rand(0,1000));

        $sql = self::INSERT_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("birthdate", \DateTime::createFromFormat('Y-m-d', $user->getBirthDate()), 'date');
        $stmt->bindValue("password", $user->getPassword(), 'string');
        $stmt->bindValue("hash", $verificationHash, 'string');
        $stmt->bindValue("created_at", $user->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $user->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();

        return $verificationHash;
    }

    public function delete($userId)
    {
        $sql = self::DELETE_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $userId['id'], 'integer');
        $stmt->execute();
    }

    public function get($userId){
        $sql = self::SELECT_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $userId, 'integer');
        $stmt->execute();

        $user =  $stmt->fetch();
        if(isset($user['password'])){
            unset($user['password']);
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

    public function changePassword($userId, $oldPassword, $newPassword){
        $sql = self::CHECK_PASS_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $userId, 'integer');
        $stmt->bindValue("password", $oldPassword, 'string');
        $stmt->execute();

        $count = $stmt->fetch()['count'];

        if($count == 0)
            return false;

        $sql = self::CHANGE_PASS_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $userId, 'integer');
        $stmt->bindValue("password", $newPassword, "string");
        $stmt->execute();

        return true;
    }


    public function verify($verificationHash){

        $sql = self::VERIFY_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("hash", $verificationHash, 'string');
        $stmt->execute();

    }

}
