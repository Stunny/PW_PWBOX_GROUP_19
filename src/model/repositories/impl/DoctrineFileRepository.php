<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/14/2018
 * Time: 9:40 PM
 */

namespace PWBox\model\repositories\impl;


use PWBox\model\File;
use PWBox\model\repositories\FileRepository;
use Doctrine\DBAL\Connection;

class DoctrineFileRepository implements FileRepository
{

    private $connection;

    private const POST_QUERY = 'INSERT INTO `file`(`filename`, `creator`, `folder`) VALUES (:filename, :creator, :folder);';
    private const DELETE_QUERY = 'DELETE FROM `file` WHERE (`id` = :id);';
    private const GET_DATA_QUERY = 'SELECT * FROM `file` WHERE `id` = :id;';
    private const DOWNLOAD_DATA_QUERY = 'SELECT * FROM `file`;';


    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function post(File $file): File
    {
        $sql = self::POST_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("filename", $file->getName(), 'string');
        $stmt->bindValue("creator", $file->getCreador(), 'integer');
        $stmt->bindValue("folder", $file->getFolder(), 'integer');
        $stmt->execute();

        $sql = "SELECT `id` FROM `file` ORDER BY `id` DESC LIMIT 1;";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return new File($stmt->fetch()['id'], null, null, null, null, null, null);
        /* TODO: Implement post() method. Una vez cargado el archivo en la carpeta del usuario y sus datos en la base
         *   de datos, devolver un objeto de clase File el cual contenga la ID del archivo en la BD
        */
    }

    public function download(File $file): File
    {

        // TODO: Implement download() method. Devolver un objeto de la clase File cuyo atributo `file` contenga el archivo
    }

    public function delete(File $file)
    {
        $sql = self::DELETE_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $file->getId(), 'integer');
        $stmt->execute();
        // TODO: Implement delete() method. Eliminar archivo del sistema y de la base de datos
    }

    public function getData(File $file): File
    {
        $sql = self::GET_DATA_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $file->getId(), 'integer');
        $stmt->execute();
        $query_result = $stmt->fetch();
        return new File($file->getId(), $query_result['filename'], $query_result['creator'], $query_result['folder'], $query_result['created_at'], $query_result['updated_at'], null);
    }

    public function updateData(File $file): File
    {
        // TODO: Implement updateData() method. Devolver el objeto de la clase File con los nuevos datos y con su atributo `file` a null
    }
}