<?php

/**
 * Created by PhpStorm.
 * User: angel
 * Date: 10/04/2018
 * Time: 12:33
 */
    class User{

    private $userName;
    private $password;
    private $email;
    private $birthDate;
    private $imagePath;

        public function __construct($userName, $password, $email, $birthDate, $imagePath) {
            $this->userName = $userName;
            $this->password = $password;
            $this->email = $email;
            $this->birthDate = $birthDate;
            $this->imagePath = $imagePath;
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

        /**
         * @return mixed
         */
        public function getImagePath()
        {
            return $this->imagePath;
        }

        /**
         * @param mixed $imagePath
         */
        public function setImagePath($imagePath)
        {
            $this->imagePath = $imagePath;
        }



}