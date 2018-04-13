<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/13/2018
 * Time: 3:39 PM
 */

namespace PWBox\model\repositories\impl;


use PWBox\model\Folder;
use PWBox\model\FolderRepository;
use PWBox\model\User;

class DoctrineFolderRepository implements FolderRepository
{

    public function create($folderName, User $creator)
    {
        // TODO: Implement create() method.
    }

    public function update(User $creator, Folder $folder)
    {
        // TODO: Implement update() method.
    }

    public function get(int $id): Folder
    {
        // TODO: Implement get() method.
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }
}