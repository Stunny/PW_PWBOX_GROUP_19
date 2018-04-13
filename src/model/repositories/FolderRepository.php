<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:04 PM
 */

namespace PWBox\model;

use PWBox\model\Folder;


interface FolderRepository
{
    public function create($folderName, User $creator);

    public function update(User $creator, Folder $folder);

    public function get(int $id) : Folder;

    public function delete(int $id);
}