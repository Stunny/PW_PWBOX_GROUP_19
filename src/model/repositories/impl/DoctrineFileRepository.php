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
use PWBox\model\repositories\FileRepository;
use Doctrine\DBAL\Connection;

class DoctrineFileRepository implements FileRepository
{

    private $connection;

    private const POST_QUERY = 'INSERT INTO `file`(`name`, `creator`, `folder`) VALUES (:filename, :creator, :folder);';
    private const DELETE_QUERY = 'DELETE FROM `file` WHERE (`id` = :id) AND `creator` = :id_usuari AND `folder` = :id_folder;';
    private const GET_DATA_QUERY = 'SELECT * FROM `file` WHERE `id` = :id AND `folder` = (SELECT `id` FROM `folder` WHERE `id` = :id_folder AND `creador` = :id_user);';
    private const DOWNLOAD_DATA_QUERY = 'SELECT * FROM `file`;';
    private const UPDATE_DATA = 'UPDATE `file` SET `name` = :filename, `folder` = :folder WHERE (`creator` = :userID AND `id` = :fileID);';


    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function post(int $userID, int $folderID, $fileName): int
    {
        $folderRepo = new DoctrineFolderRepository($this->connection);
        $folderInfo = $folderRepo->get($folderID, $userID);
        if ($folderInfo->getNom() != null){
            echo "good";
            $sql = self::POST_QUERY;
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue("filename", $fileName, 'string');
            $stmt->bindValue("creator", $folderInfo->getCreador(), 'integer');
            $stmt->bindValue("folder", $folderInfo->getId(), 'integer');
            $stmt->execute();

            $sql = "SELECT `id` FROM `file` ORDER BY `id` DESC LIMIT 1;";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            //TODO: INSERTAR ARCHIVO EN LA CARPETA CORRESPONDIENTE DEL USUARIO
            return $stmt->fetch()['id'];
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
            return -1;
        }
    }

    public function download(File $file): File
    {
        // TODO: Implement download() method. Devolver un objeto de la clase File cuyo atributo `file` contenga el archivo
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
            // TODO: Implement updateData() method. Devolver el objeto de la clase File con los nuevos datos y con su atributo `file` a null
        }else{
            return new File(null, null, null, null, null, null, null);
        }
    }
}