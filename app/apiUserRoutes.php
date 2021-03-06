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

    $this->get('/space[/]', 'PWBox\controller\UserController:getUsedSpace')->setName('get-used-space');

    $this->post('[/]', 'PWBox\controller\UserController:put')->setName('update-user');

    $this->post('/password[/]', 'PWBox\controller\UserController:changePassword')->setName('change-user-password');

    $this->post('/mail[/]', 'PWBox\controller\UserController:changeMail')->setName('change-user-mail');

    $this->post('/profileImg[/]', 'PWBox\controller\UserController:changeProfileImage')->setName('change-user-img');

    $this->post('/delete[/]', 'PWBox\controller\UserController:delete')->setName('delete-user');

//---------------------------------------------------------Folder
    $this->post('/folder[/]', 'PWBox\controller\FolderController:post')->setName('post-folder');

    $this->get('/folder/{folderID}[/]', 'PWBox\controller\FolderController:get')->setName('get-folder');

    $this->post('/folder/{folderID}[/]', 'PWBox\controller\FolderController:put')->setName('update-folder');

    $this->delete('/folder/{folderID}[/]', 'PWBox\controller\FolderController:delete')->setName('delete-folder');

    $this->get('/folder/{folderID}/tree[/]', 'PWBox\controller\FolderController:getTree')->setName('get-folder-tree');

    $this->get('/folder/{folderID}/content[/]', 'PWBox\controller\FolderController:getContent')->setName('get-folder-content');

    $this->post('/folder/{folderID}/share/{userEmail}[/]', 'PWBox\controller\FolderController:shareFolder')->setName('share-folder');


//----------------------------------------------------------File
    $this->get('/folder/{folderID}/file/{fileID}[/]', PWBox\controller\FileControllers\GetFileController::class)->setName('get-file');

    $this->post('/folder/{folderID}/file/{fileID}[/]', PWBox\controller\FileControllers\PutFileController::class)->setName('update-file');

    $this->delete('/folder/{folderID}/file/{fileID}[/]', PWBox\controller\FileControllers\DeleteFileController::class)->setName('delete-file');

//-Upload and Download

    $this->get('/folder/{folderID}/file/{fileID}/download[/]', PWBox\controller\FileControllers\DownloadFileController::class)->setName('download-file');

    $this->post('/folder/{folderID}/file[/]', PWBox\controller\FileControllers\PostFileController::class)->setName('upload-file');

})->add(\PWBox\controller\middleware\ApiAuthenticationMiddleware::class);
