<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:04 PM
 */

namespace PWBox\model\repositories;

use PWBox\model\File;
use PWBox\model\Folder;

interface FileRepository
{
    public function post(int $userID, int $folderID, $file): int;

    public function download(File $file, Folder $folder);

    public function delete($fileId, $folderID, $userId);

    public function getData(File $file): File;

    public function updateData(Folder $folder, $userID, $newName, $fileId);

    public function getFileId(&$userId, $folderId);

    public function getFileByName($filename, $userId);

    public function canEdit($fileId, $folderPath, $userId, $folderId);
}