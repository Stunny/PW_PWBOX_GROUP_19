<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:04 PM
 */

namespace PWBox\model\repositories;

use PWBox\model\Folder;


interface FolderRepository
{
    public function create(int $userId, Folder $folder);

    public function update(Folder $folder, int $userID);

    public function get(int $folderID, int $userID) : Folder;

    public function getByName($folderName, int $userID);

    public function getByPath($folderPath);

    public function delete(int $folderID, int $userID);

    public function shareFolder(int $folderID, int $userID, $email, $role);

    public function getPathAndId($userId);

    public function mySharedFolders($userId);

}