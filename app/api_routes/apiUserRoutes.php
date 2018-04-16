<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 11:36 PM
 */

$this->post('', PWBox\controller\UserControllers\PostUserController::class)->setName('post-user');

$this->get('/{userID}', PWBox\controller\UserControllers\GetUserController::class)->setName('get-user');

$this->put('/{userID}', PWBox\controller\UserControllers\UpdateUserController::class)->setName('update-user');

$this->delete('/{userID}', PWBox\controller\UserControllers\DeleteUserController::class)->setName('delete-user');

//---------------------------------------------------------Folder
$this->post('/{userID}/folder', PWBox\controller\FolderControllers\PostFolderController::class)->setName('post-folder');

$this->get('/{userID}/folder/{folderID}', PWBox\controller\FolderControllers\GetFolderController::class)->setName('get-folder');

$this->put('/{userID}/folder/{folderID}', PWBox\controller\FolderControllers\UpdateFolderController::class)->setName('update-folder');

$this->delete('/{userID}/folder/{folderID}', PWBox\controller\FolderControllers\DeleteFolderController::class)->setName('delete-folder');

//----------------------------------------------------------File
$this->get('/{userID}/folder/{folderID}/file/{fileID}', PWBox\controller\FileControllers\GetFileController::class)->setName('get-file');

$this->put('/{userID}/folder/{folderID}/file/{fileID}', PWBox\controller\FileControllers\PutFileController::class)->setName('update-file');

$this->delete('/{userID}/folder/{folderID}/file/{fileID}', PWBox\controller\FileControllers\DeleteFileController::class)->setName('delete-file');

//-Upload and Download

$this->get('/{userID}/folder/{folderID}/file/{fileID}/download', PWBox\controller\FileControllers\DownloadFileController::class)->setName('download-file');

$this->post('/{userID}/folder/{folderID}/file', PWBox\controller\FileControllers\PostFileController::class)->setName('upload-file');
