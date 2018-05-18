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

    private const LOGIN_WITH_EMAIL_QUERY = 'SELECT id from `user` where `email`= :email and `password` = md5(:password)';
    private const LOGIN_WITH_UNAME_QUERY = 'select id from `user` where `username`=:username and `password` = md5(:password)';
    
    private const INSERT_USER_QUERY = 'INSERT INTO `user`(`username`, `email`, `birthdate`, `password`, `created_at`, `updated_at`, `verificationHash`) VALUES(:username, :email, :birthdate, md5(:password), :created_at, :updated_at, :hash);';
    private const SELECT_QUERY = 'SELECT * FROM `user` WHERE (`id` = :id);';
    private const SELECT_ROOT_FOLDER = 'select `id` from folder where (`creador`=:userId and `nom`=:userName);';
    private const UPDATE_QUERY = 'UPDATE `user` SET `username` = :username, `email` = :email, `birthdate` = :birthdate WHERE `id` = :id;';
    private const UPDATE_PASSWORD = 'UPDATE `user` SET `password` = :password WHERE `id` = :id;';
    private const DELETE_QUERY = 'DELETE FROM `user` WHERE (`id` = :id);';
    private const VERIFY_QUERY = 'update user set verified = true where verificationHash = :hash;';

    private const CHECK_PASS_QUERY = 'select count(*) as count from user where id = :id and password = md5(:password);';
    private const CHANGE_PASS_QUERY = 'update user set password = md5(:password) where id = :id;';

    private const CHECK_EMAIL_QUERY = 'select count(*) as count from user where `email`=:email';
    private const CHECK_UNAME_QUERY = 'select count(*) as count from user where `username`=:username';

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user)
    {
        $verificationHash = md5(rand(0,1000));

        $sql = self::INSERT_USER_QUERY;
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

        $user = $stmt->fetch();
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
            $stmt->bindValue("birthdate", $user->getBirthDate(), 'string');
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

    public function login($login, $userPassword){

        if($this->checkEmail($login)) {
            $sql = self::LOGIN_WITH_EMAIL_QUERY;
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("email", $login, 'string');
        }else{
            $sql = self::LOGIN_WITH_UNAME_QUERY;
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("username", $login, 'string');
        }

        $stmt->bindValue("password", $userPassword, "string");
        $stmt->execute();
        $id = $stmt->fetch()['id'];

        return $id;
    }

    public function getRootFolderId($userId)
    {

        $userName = $this->get($userId)['username'];

        $sql = self::SELECT_ROOT_FOLDER;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("userId", $userId, 'integer');
        $stmt->bindValue("userName", $userName, 'string');
        $stmt->execute();

        $id = $stmt->fetch()['id'];

        return $id;
    }

    private function checkEmail($email) {
       if ( strpos($email, '@') !== false ) {
          $split = explode('@', $email);
          return (strpos($split['1'], '.') !== false ? true : false);
       } else {
            return false;
       }
    }

    public function exists($username, $email){
        $sql = self::CHECK_UNAME_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("username", $username, 'string');
        $stmt->execute();

        $count = $stmt->fetch()['count'];

        if($count != 0)
            return true;

        $sql = self::CHECK_EMAIL_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("email", $email, 'string');
        $stmt->execute();

        $count = $stmt->fetch()['count'];

        if($count != 0)
            return true;

        return false;
    }

}
