<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 3:39 PM
 */

namespace PWBox\model\repositories\impl;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\InvalidFieldNameException;
use function FastRoute\cachedDispatcher;
use PWBox\model\Folder;
use PWBox\model\repositories\FolderRepository;
use PWBox\model\User;

class DoctrineFolderRepository implements FolderRepository
{

    private const USER_FOLDERS_DIR = "/home/vagrant/pwbox/appdata/user_folders/";

    private $connection;

    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private const INSERT_FOLDER_QUERY = 'INSERT INTO `folder`(`creador`, `nom`, `path`, `created_at`, `updated_at`) VALUES (:creador, :nom, :path, :created_at, :updated_at);';
    private const UPDATE_QUERY = 'UPDATE `folder` SET `nom` = :nom, `path` = :path WHERE `id` = :id;';
    private const INSERT_ROLES_QUERY = 'INSERT INTO `role`(`usuari`, `folder`, `role`, `created_at`, `updated_at`) VALUES (:id_creador, :id_folder, :role, :created_at, :updated_at);';
    private const SHARE_QUERY = 'INSERT INTO `role`(`usuari`, `folder`, `role`) VALUES (:id_creador, :id_folder, :role);';
    private const NEW_FOLDER_ID = 'SELECT `id` FROM `folder` WHERE `creador` = :id_creador ORDER BY `id` DESC LIMIT 1;';
    private const SELECT_QUERY = 'SELECT * FROM `folder` WHERE (`id` = :id) AND `id` = (SELECT `folder` FROM `role` WHERE `folder` = :id AND `usuari` = :id_usuari);';
    private const SELECT_QUERY_2 = 'SELECT * FROM `folder` WHERE (`id` = :id);';
    private const SELECT_BY_NAME_QUERY = 'select * from `folder` where(`creador`=:userID and `nom`=:folderName)';
    private const FOLDER_DELETE_QUERY = 'DELETE FROM `folder` WHERE (`id` = :id) AND `creador` = :id_usuari;';
    private const ROLE_DELETE_QUERY = 'DELETE FROM `role` WHERE `usuari` = :id_usuari AND `folder` = :id_folder;';
    private const ACCESSIBLE_FOLDERS = 'SELECT `folder` FROM `role` WHERE `usuari` = :id_usuari;';
    private const GET_FOLDER_PATH_ID = 'SELECT `id`, `path` FROM `folder` WHERE `creador` = :id_creador;';


    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create(int $creatorId, Folder $folder)
    {
        if(file_exists(self::USER_FOLDERS_DIR . $folder->getPath())){
            return 409;
        }

        $sql = self::INSERT_FOLDER_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("creador", $creatorId, 'integer');
        $stmt->bindValue("nom", $folder->getNom(), 'string');
        $stmt->bindValue("path", $folder->getPath(), 'string');
        $stmt->bindValue("created_at", $folder->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $folder->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();

        $sql = self::NEW_FOLDER_ID;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_creador", $creatorId, 'integer');
        $stmt->execute();
        $folderID = $stmt->fetch();

        $sql = self::INSERT_ROLES_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_creador", $creatorId, 'integer');
        $stmt->bindValue("id_folder", intval($folderID['id']), 'integer');
        $stmt->bindValue("role", "admin", 'string');
        $stmt->bindValue("created_at", $folder->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $folder->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();

        mkdir(self::USER_FOLDERS_DIR.$folder->getPath(), 0777, true);
        return 200;
    }

    public function update(Folder $folder, int $userID)
    {
        $olderFolder = $this->get($folder->getId(), $userID);
        $newPath = str_replace($olderFolder->getNom(), $folder->getNom(), $olderFolder->getPath());

        if ($this->get($folder->getId(), $userID)->getCreador() == $userID){
            $sql = self::UPDATE_QUERY;
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("nom", $folder->getNom(), 'string');
            $stmt->bindValue("path", $newPath, 'string');
            $stmt->bindValue("id", $folder->getId(), 'integer');
            $stmt->execute();
            return true;
        }else{
            return false;
        }
    }

    public function get(int $folderID, int $userID): Folder
    {
        $sql = self::SELECT_QUERY_2;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $folderID, 'integer');
        //$stmt->bindValue("id_usuari", $userID, 'integer');
        $stmt->execute();
        $aux1 = $stmt->fetch();

        if ($aux1 != null){
            //si la carpeta es suya, es a la que le asignaron los permisos directamente o es una subcarpeta de la carpeta a la que le dieron permisos
            $folderPath = $aux1['path'];

            $sql = self::ACCESSIBLE_FOLDERS;
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("id_usuari", $userID, 'integer');

            $stmt->execute();
            $aux = $stmt->fetchAll();
            foreach ($aux as $valor){
                $sharedFolderId = $valor['folder'];

                $sql = self::SELECT_QUERY;
                $stmt = $this->connection->prepare($sql);
                $stmt->bindValue("id", $sharedFolderId, 'integer');
                $stmt->bindValue("id_usuari", $userID, 'integer');
                $stmt->execute();
                $aux = $stmt->fetch();
                $folderPathLength = strlen($aux['path']);
                if (substr($folderPath, 0, $folderPathLength) == $aux['path']){
                    return new Folder($aux1['id'], $aux1['creador'], $aux1['nom'], $aux1['path'], $aux1['created_at'], $aux1['updated_at']);
                }
            }
            return new Folder(null, null ,null, null, null, null);
        }else{
            //si no tiene permisos. return redundante
            return new Folder(null, null ,null, null, null, null);
        }
    }

    public function getByName($folderName, int $userID)
    {
        $sql = self::SELECT_BY_NAME_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("folderName", $folderName, 'string');
        $stmt->bindValue("userID", $userID, 'integer');

        $stmt->execute();
        $aux = $stmt->fetch();
        return new Folder($aux['id'], $aux['creador'], $aux['nom'], $aux['path'], $aux['created_at'], $aux['updated_at']);
    }

    public function delete(int $folderID, int $userID)
    {
        $sql = "SELECT `usuari` FROM `role` WHERE `folder` = :id AND `usuari` = :id_usuari;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_usuari", $userID, 'integer');
        $stmt->bindValue("id", $folderID, 'integer');
        $stmt->execute();
        $userID = $stmt->fetch();

        if (isset($userID)){
            $sql = self::ROLE_DELETE_QUERY;
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("id_usuari", $userID['usuari'], 'integer');
            $stmt->bindValue("id_folder", $folderID, 'integer');
            $stmt->execute();

            $sql = self::FOLDER_DELETE_QUERY;
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("id", $folderID, 'integer');
            $stmt->bindValue("id_usuari", $userID['usuari'], 'integer');
            $stmt->execute();
            if (isset($userID['usuari'])){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function shareFolder(int $folderID, int $userID, $email, $roleToAssing)
    {
        //var_dump($userID);
        if ($userID != null){
            //comprueba si el usuario que quiere compartir existe
            $sql = "SELECT username FROM `user` WHERE `id` = :id_usuari;";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("id_usuari", $userID, 'integer');
            $stmt->execute();
            $sharingUser = $stmt->fetch();
            //var_dump($sharingUser);
            if ($sharingUser != null){
                //comprueba si la carpeta a compartir existe
                $sql = "SELECT nom FROM `folder` WHERE `creador` = :id_usuari AND `id` = :id_carpeta;";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindValue("id_usuari", $userID, 'integer');
                $stmt->bindValue("id_carpeta", $folderID, 'integer');
                $stmt->execute();
                $folder = $stmt->fetch();
                //var_dump($folder);
                if ($folder != null){
                    //comprueba si el usuario al que compartir existe
                    $sql = "SELECT id FROM `user` WHERE `email` = :email_usuari;";
                    $stmt = $this->connection->prepare($sql);
                    $stmt->bindValue("email_usuari", $email, 'string');
                    $stmt->execute();
                    $sharedUser = $stmt->fetch();
                    //var_dump($sharedUser);
                    if ($sharedUser != null){
                        //comprueba si el usuario que quiere compartir es admin
                        $sql = "SELECT role FROM `role` WHERE `usuari` = :id_usuari AND `folder` = :id_carpeta;";
                        $stmt = $this->connection->prepare($sql);
                        $stmt->bindValue("id_usuari", $userID, 'integer');
                        $stmt->bindValue("id_carpeta", $folderID, 'integer');
                        $stmt->execute();
                        $role = $stmt->fetch();
                        //var_dump($role);
                        if ($role['role'] === "admin"){
                            //comprobamos que no haya una relacion ya existente
                            $sql = "SELECT role FROM `role` WHERE `usuari` = :id_usuari AND `folder` = :id_carpeta;";
                            $stmt = $this->connection->prepare($sql);
                            $stmt->bindValue("id_usuari", $sharedUser['id'], 'integer');
                            $stmt->bindValue("id_carpeta", $folderID, 'integer');
                            $stmt->execute();
                            $exist = $stmt->fetch();
                            //var_dump($exist);
                            if ($exist['role'] == null){
                                //insertamos carpeta compartida en la tabla role
                                $sql = self::SHARE_QUERY;
                                $stmt = $this->connection->prepare($sql);
                                $stmt->bindValue("id_creador", $sharedUser['id'], 'integer');
                                $stmt->bindValue("id_folder", $folderID, 'integer');
                                $stmt->bindValue("role", $roleToAssing, 'string');
                                $stmt->execute();
                            }else{
                                //actualizamos con el nuevo rol
                                $sql = "UPDATE `role` SET `role` = :role WHERE `usuari` = :id_creador AND `folder` = :id_folder;";
                                $stmt = $this->connection->prepare($sql);
                                $stmt->bindValue("id_creador", $sharedUser['id'], 'integer');
                                $stmt->bindValue("id_folder", $folderID, 'integer');
                                $stmt->bindValue("role", $roleToAssing, 'string');
                                $stmt->execute();
                            }
                        }else{
                            //echo "usuario que quiere compartir no es admin";
                            return 401;
                        }
                    }else{
                        //echo "usuario al que compartir no existe";
                        return 404;
                    }
                }else{
                    //echo "carpeta no existe";
                    return 404;
                }
            }else{
                //echo "el usuario que quiere compartir no existe";
                return 404;
            }
        }else{
            //echo "no hay id de usuario";
            return 404;
        }
        return 200;
    }

    public function getPathAndId($userId)
    {
        $sql = self::GET_FOLDER_PATH_ID;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_creador", $userId, 'integer');
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
