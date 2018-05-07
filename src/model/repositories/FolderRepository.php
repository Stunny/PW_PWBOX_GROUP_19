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

    public function update(Folder $folder);

    public function get(int $folderID, int $userID) : Folder;

    public function delete(int $id);
}