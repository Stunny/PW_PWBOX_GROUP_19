<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 11:36 PM
 */

$this->post('[/]', 'PWBox\controller\UserController:post')->setName('post-user');

$this->get('/verify/{hash}[/]', 'PWBox\controller\UserController:verifyEmail')->setName('verify-user');

$this->group('/{userID}', function (){

    $this->get('[/]', 'PWBox\controller\UserController:get')->setName('get-user');

    $this->put('[/]', 'PWBox\controller\UserController:put')->setName('update-user');

    $this->put('/password[/]', 'PWBox\controller\UserController:changePassword')->setName('change-user-password');

    $this->delete('[/]', 'PWBox\controller\UserController:delete')->setName('delete-user');

//---------------------------------------------------------Folder
    $this->post('/folder[/]', 'PWBox\controller\FolderController:post')->setName('post-folder');

    $this->get('/folder/{folderID}[/]', 'PWBox\controller\FolderController:get')->setName('get-folder');

    $this->put('/folder/{folderID}[/]', 'PWBox\controller\FolderController:put')->setName('update-folder');

    $this->delete('/folder/{folderID}[/]', 'PWBox\controller\FolderController:delete')->setName('delete-folder');

    $this->get('/folder/{folderID}/tree[/]', 'PWBox\controller\FolderController:getTree')->setName('get-folder-tree');

//----------------------------------------------------------File
    $this->get('/folder/{folderID}/file/{fileID}[/]', PWBox\controller\FileControllers\GetFileController::class)->setName('get-file');

    $this->put('/folder/{folderID}/file/{fileID}[/]', PWBox\controller\FileControllers\PutFileController::class)->setName('update-file');

    $this->delete('/folder/{folderID}/file/{fileID}[/]', PWBox\controller\FileControllers\DeleteFileController::class)->setName('delete-file');

//-Upload and Download

    $this->get('/folder/{folderID}/file/{fileID}/download[/]', PWBox\controller\FileControllers\DownloadFileController::class)->setName('download-file');

    $this->post('/folder/{folderID}/file[/]', PWBox\controller\FileControllers\PostFileController::class)->setName('upload-file');

})/*->add(\PWBox\controller\middleware\ApiAuthenticationMiddleware::class)*/;
