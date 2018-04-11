<?php

/**
 * Created by PhpStorm.
 * User: angel
 * Date: 10/04/2018
 * Time: 12:28
 */

namespace PWBox\model;

class DatabaseConector
{
    private $connexion;

    private function connect(){
        try{
            $this->connexion =  new PDO("mysql:host=localhost;dbname=PWBOX", "root", "secret", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            return true;
        }catch (PDOException $e){
            echo('Error: '.$e->getMessage().' Code: '.$e->getCode());
        }

    }

    public function registerUser(User $user){
        if ($this->connect()){
            $this->connexion->query("INSERT INTO Usuari (nom, contrasenya, email, birthdate, profile_image) 
              VALUES (\"".$user->getUserName()."\", \"".$user->setPassword()."\", \"".$user->setEmail()."\",
               \"".$user->getBirthDate()->format('Y-m-d')."\", \"".$user->getProfileImgPath()."\");");
        }
    }

}