<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 11:36 PM
 */

$this->post('[/]', 'PWBox\controller\UserController:post')->setName('post-user');

$this->get('/{userID}[/]', 'PWBox\controller\UserController:get')->setName('get-user');

$this->put('/{userID}[/]', 'PWBox\controller\UserController:put')->setName('update-user');

$this->put('/{userID}/password[/]', 'PWBox\controller\UserController:changePassword')->setName('change-user-password');

$this->delete('/{userID}[/]', 'PWBox\controller\UserController:delete')->setName('delete-user');

$this->get('/verify/{hash}[/]', 'PWBox\controller\UserController:verifyEmail')->setName('verify-user');


//---------------------------------------------------------Folder
$this->post('/{userID}/folder[/]', 'PWBox\controller\FolderController:post')->setName('post-folder');

$this->get('/{userID}/folder/{folderID}[/]', 'PWBox\controller\FolderController:get')->setName('get-folder');

$this->put('/{userID}/folder/{folderID}[/]', 'PWBox\controller\FolderController:put')->setName('update-folder');

$this->delete('/{userID}/folder/{folderID}[/]', 'PWBox\controller\FolderController:delete')->setName('delete-folder');

$this->get('/{userID}/folder/{folderID}/tree[/]', 'PWBox\controller\FolderController:getTree')->setName('get-folder-tree');

//----------------------------------------------------------File
$this->get('/{userID}/folder/{folderID}/file/{fileID}[/]', PWBox\controller\FileControllers\GetFileController::class)->setName('get-file');

$this->put('/{userID}/folder/{folderID}/file/{fileID}[/]', PWBox\controller\FileControllers\PutFileController::class)->setName('update-file');

$this->delete('/{userID}/folder/{folderID}/file/{fileID}[/]', PWBox\controller\FileControllers\DeleteFileController::class)->setName('delete-file');

//-Upload and Download

$this->get('/{userID}/folder/{folderID}/file/{fileID}/download[/]', PWBox\controller\FileControllers\DownloadFileController::class)->setName('download-file');

$this->post('/{userID}/folder/{folderID}/file[/]', PWBox\controller\FileControllers\PostFileController::class)->setName('upload-file');
