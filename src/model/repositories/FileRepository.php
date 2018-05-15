<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:04 PM
 */

namespace PWBox\model\repositories;

use PWBox\model\File;

interface FileRepository
{
    public function post(int $userID, int $folderID, $fileName): int;

    public function download(File $file): File;

    public function delete(File $file);

    public function getData(File $file): File;

    public function updateData(File $file, $userID, $newName): File;

}