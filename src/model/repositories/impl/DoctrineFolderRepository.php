<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 3:39 PM
 */

namespace PWBox\model\repositories\impl;


use Doctrine\DBAL\Connection;
use PWBox\model\Folder;
use PWBox\model\repositories\FolderRepository;
use PWBox\model\User;

class DoctrineFolderRepository implements FolderRepository
{

    private $connection;

    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private const INSERT_QUERY = 'INSERT INTO `folder`(`creador`, `nom`, `path`, `created_at`, `updated_at`) VALUES (:creador, :nom, :path, :created_at, :updated_at)';
    private const SELECT_QUERY = 'SELECT * FROM `folder` WHERE (`id` = :id)';
    private const UPDATE_QUERY = 'UPDATE `folder` SET `creador` = :creador, `nom` = :nom, `path` = :path WHERE `id` = :id';
    private const DELETE_QUERY = 'DELETE FROM `folder` WHERE (`id` = :id)';

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create(int $creatorId, Folder $folder)
    {
        $sql = self::INSERT_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("creador", $creatorId, 'integer');
        $stmt->bindValue("nom", $folder->getNom(), 'string');
        $stmt->bindValue("path", $folder->getPath(), 'string');
        $stmt->bindValue("created_at", $folder->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $folder->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();
    }

    public function update(Folder $folder)
    {
        $sql = self::UPDATE_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("creador", $folder->getCreador(), 'integer');
        $stmt->bindValue("nom", $folder->getNom(), 'string');
        $stmt->bindValue("path", $folder->getPath(), 'string');
        $stmt->bindValue("created_at", $folder->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $folder->getUpdatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();
    }

    public function get(int $id): Folder
    {
        $sql = self::SELECT_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $id, 'integer');
        $stmt->execute();
    }

    public function delete(int $id)
    {
        $sql = self::DELETE_QUERY;
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $id, 'integer');
        $stmt->execute();
    }
}