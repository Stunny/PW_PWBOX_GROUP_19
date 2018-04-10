<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:04 PM
 */

namespace PWBox\model;


interface FolderRepository
{
    public function createFolder($folderName, User $creator);
}