<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:04 PM
 */

namespace PWBox\model;


interface FileRepository
{
    public function post(User $user, Folder $folder_name, File $file);

    public function get(User $user, Folder $folder,  $file_name);
}