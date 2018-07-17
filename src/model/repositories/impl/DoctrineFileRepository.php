<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/14/2018
 * Time: 9:40 PM
 */

namespace PWBox\model\repositories\impl;


use Doctrine\DBAL\Logging\EchoSQLLogger;
use function PHPSTORM_META\type;
use PWBox\model\File;
use PWBox\model\Folder;
use PWBox\model\repositories\FileRepository;
use Doctrine\DBAL\Connection;

class DoctrineFileRepository implements FileRepository
{

    private $connection;

    private const USER_FOLDERS_DIR = "/home/vagrant/pwbox/appdata/user_folders/";

    private const POST_QUERY = 'INSERT INTO `file`(`name`, `creator`, `folder`) VALUES (:filename, :creator, :folder);';
    //private const DELETE_QUERY = 'DELETE FROM `file` WHERE (`id` = :id) AND `creator` = :id_usuari AND `folder` = :id_folder;';
    private const DELETE_QUERY = 'DELETE FROM `file` WHERE (`id` = :id)  AND `folder` = :id_folder;';
    private const GET_DATA_QUERY = 'SELECT * FROM `file` WHERE `id` = :id AND `folder` = (SELECT `id` FROM `folder` WHERE `id` = :id_folder AND `creador` = :id_user);';
    private const UPDATE_DATA = 'UPDATE `file` SET `name` = :filename WHERE (`folder` = :folder AND `id` = :fileID);';
    private const GET_FILE_ID = 'SELECT `id` FROM `file` WHERE `folder` = :id_folder AND `creator` = :id_creador ;';


    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function post(int $userID, int $folderID, $file): int
    {
        $folderRepo = new DoctrineFolderRepository($this->connection);
        $folderInfo = $folderRepo->get($folderID, $userID);

        $sql = 'SELECT * FROM `file` WHERE `name` = :file_name;';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("file_name", $file->getClientFileName(), 'string');
        $stmt->execute();

        $sql = 'SELECT `role` FROM `role` WHERE `folder` = :id_folder AND `usuari` = :id_usuari;';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_folder", $folderID, 'integer');
        $stmt->bindValue("id_usuari", $userID, 'integer');
        $stmt->execute();
        $role = $stmt->fetch();

        if ($folderInfo->getNom() != null && $role['role'] == 'admin'){
            $sql = self::POST_QUERY;
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("filename", $file->getClientFileName(), 'string');
            $stmt->bindValue("creator", $folderInfo->getCreador(), 'integer');
            $stmt->bindValue("folder", $folderInfo->getId(), 'integer');
            $stmt->execute();

            /*
             * en caso de querer devolver los ID de los files, DESC LIMIT file.size
            $sql = "SELECT `id` FROM `file` ORDER BY `id` DESC LIMIT 1;";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            */

            return true;
        }else{
            /*
            if (carpeta no existeix){
                retornar 404
            }else {
                (carpeta existeix pero no es del usuari)
                retornar 401 unauthorised
            }
            pd: tots els camps de File a null = 404 i id = -1 igual a 401 per exemple?
            */
            return false;
        }
    }

    public function download(File $file, Folder $folder): File
    {
        var_dump($file);
        $path = self::USER_FOLDERS_DIR . $folder->getPath() . DIRECTORY_SEPARATOR . $file->getName();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file->getName() . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($path);
        exit;
        return true;
    }

    public function delete($fileId, $folderID, $userId)
    {
        $sql = '(SELECT `role` FROM `role` WHERE `usuari` = :id_user AND `folder` = :id_folder)';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_user", $userId, 'integer');
        $stmt->bindValue("id_folder", $folderID, 'integer');
        $stmt->execute();
        $role = $stmt->fetch();

        if ($role['role'] == 'admin'){
            $sql = 'SELECT `path` FROM `folder` WHERE `id` = :folderId;';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("folderId", $folderID, 'integer');
            $stmt->execute();
            $folderPath = $stmt->fetch();

            $fileId = (int) $fileId;
            $sql = 'SELECT `name` FROM `file` WHERE `id` = :fileId;';
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("fileId", $fileId, 'integer');
            $stmt->execute();
            $fileName = $stmt->fetch();

            unlink("/home/vagrant/pwbox/appdata/user_folders/" . $folderPath['path'] . '/' . $fileName['name']);

            $sql = self::DELETE_QUERY;
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("id", $fileId, 'integer');
            $stmt->bindValue("id_folder", $folderID, 'integer');
            $stmt->execute();
            return 200;
        }else{
            return 401;
        }

    }

    public function getData(File $file): File
    {
        $sql = self::GET_DATA_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $file->getId(), 'integer');
        $stmt->bindValue("id_folder", $file->getFolder(), 'integer');
        $stmt->bindValue("id_user", $file->getCreador(), 'integer');
        $stmt->execute();
        $query_result = $stmt->fetch();

        return new File($query_result['id'], $query_result['name'], $query_result['creator'], $query_result['folder'], $query_result['created_at'], $query_result['updated_at'], null);
    }

    public function updateData(Folder $folder, $userID, $newName, $fileId)
    {
        $sql = 'SELECT `name` FROM `file` WHERE `id` = :fileId;';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("fileId", (int)$fileId, 'integer');
        $stmt->execute();

        $ext = pathinfo($stmt->fetch()['name']);
        $newName = $newName . "." . $ext['extension'];

        $sql = self::UPDATE_DATA;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("filename", $newName, 'string');
        $stmt->bindValue("folder", (int)$folder->getId(), 'integer');
        $stmt->bindValue("fileID", (int)$fileId, 'integer');
        $stmt->execute();

        $this->getData(new File($fileId, null, $userID, $folder->getId(), null, null, null));
    }

    public function getFileId(&$userId, $folderId)
    {

        $sql_access = 'select usuari from role where folder=:folderID';
        $stmt = $this->connection->prepare($sql_access);
        $stmt->bindValue('folderID', $folderId, 'integer');
        $stmt->execute();

        $ids = $stmt->fetchAll();
        $hasAccess = false;
        //var_dump($userId);
        foreach ($ids as $i){
            //var_dump($i);
            if($i['usuari'] == $userId) {
                $hasAccess = true;
                break;
            }
        }
        //var_dump($hasAccess);
        if($hasAccess){
            $stmt = $this->connection->prepare("select creador from folder where id=:folderID");
            $stmt->bindValue("folderID", $folderId, 'integer');
            $stmt->execute();
            //echo "tiene permisos";
            if($ownerID = $stmt->fetch()){
                $userId = $ownerID['creador'];
            }
        }

        $sql = self::GET_FILE_ID;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_folder", $folderId, 'integer');
        $stmt->bindValue("id_creador", $userId, 'integer');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getFileByName($filename, $folderId){
        $stmt = $this->connection->prepare("select * from file where folder=:folder and name=:filename");
        $stmt->bindValue("folder", $folderId, 'integer');
        $stmt->bindValue("filename", $filename, 'string');
        $stmt->execute();

        return $stmt->fetch();
    }

    public function canEdit($fileId, $folderPath, $userId, $folderId)
    {
        while (count(explode('/', $folderPath)) != 1){
            $folderPath = str_replace('/' . explode('/', $folderPath)[count(explode('/', $folderPath)) - 1], '' , $folderPath);

            $sql = "SELECT `role` FROM `role` WHERE `folder` = :folderId AND `usuari` =:userId;";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("folderId", $folderId, 'integer');
            $stmt->bindValue("userId", $userId, 'integer');
            $stmt->execute();
            $role = $stmt->fetch();
            if (!$role){
                return false;
            }else{
                break;
            }
        }
        if (isset($role['role']) && $role['role'] == 'admin'){
            return 200;
        }else{
            return 401;
        }
    }
}