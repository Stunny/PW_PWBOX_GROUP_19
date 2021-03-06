<?php

/**
 * Created by PhpStorm.
 * User: angel
 * Date: 10/04/2018
 * Time: 12:33
 */

namespace PWBox\model;

    class User{

    private $id;
    private $userName;
    private $password;
    private $email;
    private $birthDate;
    private $verified;
    private $created_at;
    private $updated_at;

        public function __construct($id, $userName, $password, $email, $birthDate, $verified, $created_at, $updated_at) {
            $this->id = $id;
            $this->userName = $userName;
            $this->password = $password;
            $this->email = $email;
            $this->birthDate = $birthDate;
            $this->verified = $verified;
            $this->created_at = $created_at;
            $this->updated_at = $updated_at;
        }

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param mixed $id
         */
        public function setId($id): void
        {
            $this->id = $id;
        }

        /**
         * @return mixed
         */
        public function getCreatedAt()
        {
            return $this->created_at;
        }

        /**
         * @param mixed $created_at
         */
        public function setCreatedAt($created_at)
        {
            $this->created_at = $created_at;
        }

        /**
         * @return mixed
         */
        public function getUpdatedAt()
        {
            return $this->updated_at;
        }

        /**
         * @param mixed $updated_at
         */
        public function setUpdatedAt($updated_at)
        {
            $this->updated_at = $updated_at;
        }

        /**
         * @return mixed
         */
        public function getUserName()
        {
            return $this->userName;
        }

        /**
         * @param mixed $userName
         */
        public function setUserName($userName)
        {
            $this->userName = $userName;
        }

        /**
         * @return mixed
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * @param mixed $password
         */
        public function setPassword($password)
        {
            $this->password = $password;
        }

        /**
         * @return mixed
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * @param mixed $email
         */
        public function setEmail($email)
        {
            $this->email = $email;
        }

        /**
         * @return mixed
         */
        public function getBirthDate()
        {
            return $this->birthDate;
        }

        /**
         * @param mixed $birthDate
         */
        public function setBirthDate($birthDate)
        {
            $this->birthDate = $birthDate;
        }



}