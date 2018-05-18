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
    private const DELETE_QUERY = 'DELETE FROM `file` WHERE (`id` = :id) AND `creator` = :id_usuari AND `folder` = :id_folder;';
    private const GET_DATA_QUERY = 'SELECT * FROM `file` WHERE `id` = :id AND `folder` = (SELECT `id` FROM `folder` WHERE `id` = :id_folder AND `creador` = :id_user);';
    private const UPDATE_DATA = 'UPDATE `file` SET `name` = :filename, `folder` = :folder WHERE (`creator` = :userID AND `id` = :fileID);';
    private const GET_FILE_ID = 'SELECT `id` FROM `file` WHERE `folder` = :id_folder AND `creator` = :id_creador;';


    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function post(int $userID, int $folderID, $file): int
    {
        $folderRepo = new DoctrineFolderRepository($this->connection);
        $folderInfo = $folderRepo->get($folderID, $userID);
        if ($folderInfo->getNom() != null){
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

    public function delete(File $file)
    {
        $databaseFile = $this->getData($file);
        $databaseFileId = $databaseFile->getId();
        if (isset($databaseFileId) && $databaseFile->getFolder() == $file->getFolder() && $databaseFile->getCreador() == $file->getCreador()){
            $sql = self::DELETE_QUERY;
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("id", $file->getId(), 'integer');
            $stmt->bindValue("id_usuari", $file->getCreador(), 'integer');
            $stmt->bindValue("id_folder", $file->getFolder(), 'integer');
            $stmt->execute();
            return true;
        }else{
            return false;
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

    public function updateData(File $file, $userID, $newName): File
    {
        $sql = self::UPDATE_DATA;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("filename", $newName, 'string');
        $stmt->bindValue("folder", $file->getFolder(), 'integer');
        $stmt->bindValue("userID", $userID, 'integer');
        $stmt->bindValue("fileID", $file->getId(), 'integer');
        $stmt->execute();

        $file = $this->getData(new File($file->getId(), null, $userID, $file->getFolder(), null, null, null));
        if ($newName == $file->getName()){
            $sql = "SELECT * FROM `file` ORDER BY `id` DESC LIMIT 1;";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $aux = $stmt->fetch();
            return new File($aux['id'], $aux['name'], $aux['creator'], $aux['folder'], $aux['created_at'], $aux['updated_at'], null);
        }else{
            return new File(null, null, null, null, null, null, null);
        }
    }

    public function getFileId($userId, $folderId)
    {
        $sql = self::GET_FILE_ID;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_folder", $folderId, 'integer');
        $stmt->bindValue("id_creador", $userId, 'integer');
        $stmt->execute();

        return $stmt->fetchAll();
    }
}