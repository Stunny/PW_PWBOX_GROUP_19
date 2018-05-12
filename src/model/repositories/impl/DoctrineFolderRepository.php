<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 3:39 PM
 */

namespace PWBox\model\repositories\impl;


use Doctrine\DBAL\Connection;
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
    private const NEW_FOLDER_ID = 'SELECT `id` FROM `folder` WHERE `creador` = :id_creador ORDER BY `id` DESC LIMIT 1;';
    private const SELECT_QUERY = 'SELECT * FROM `folder` WHERE (`id` = :id) AND `id` = (SELECT `folder` FROM `role` WHERE `folder` = :id AND `usuari` = :id_usuari);';
    private const SELECT_BY_NAME_QUERY = 'select * from `folder` where(`creador`=:userID and `nom`=:folderName)';
    private const FOLDER_DELETE_QUERY = 'DELETE FROM `folder` WHERE (`id` = :id) AND `creador` = :id_usuari;';
    private const ROLE_DELETE_QUERY = 'DELETE FROM `role` WHERE `usuari` = :id_usuari AND `folder` = :id_folder;';


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
        $stmt->bindValue("role", "read", 'string');
        $stmt->bindValue("created_at", $folder->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $folder->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();

        mkdir(self::USER_FOLDERS_DIR.$folder->getPath(), 0777, true);
        return 200;
    }

    public function update(Folder $folder, int $userID)
    {
        if ($this->get($folder->getId(), $userID)->getCreador() == $userID){
            $name = $folder->getNom();
            if (isset($name)){
                $sql = self::UPDATE_QUERY;
                $stmt = $this->connection->prepare($sql);
                $stmt->bindValue("nom", $folder->getNom(), 'string');
                $stmt->bindValue("path", $this->get($folder->getId(), $userID)->getPath(), 'string');
                $stmt->bindValue("id", $folder->getId(), 'integer');
                $stmt->execute();
            }else{
                $sql = self::UPDATE_QUERY;
                $stmt = $this->connection->prepare($sql);
                $stmt->bindValue("nom", $this->get($folder->getId(), $userID)->getNom(), 'string');
                $stmt->bindValue("path", $folder->getPath(), 'string');
                $stmt->bindValue("id", $folder->getId(), 'integer');
                $stmt->execute();
            }
            return true;
        }else{
            return false;
        }
    }

    public function get(int $folderID, int $userID): Folder
    {
        $sql = self::SELECT_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $folderID, 'integer');
        $stmt->bindValue("id_usuari", $userID, 'integer');
        $stmt->execute();
        $aux = $stmt->fetch();
        return new Folder($aux['id'], $aux['creador'], $aux['nom'], $aux['path'], $aux['created_at'], $aux['updated_at']);
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
}
