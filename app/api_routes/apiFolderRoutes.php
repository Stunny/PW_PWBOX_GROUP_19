<?php
/**
 * Created by PhpStorm.
 * Folder: Alex
 * Date: 4/10/2018
 * Time: 11:36 PM
 */

$this->post('', 'PWBox\controller\FolderControllers\PostFolderController')->setName('post-folder');

$this->get('/{folderID}', 'PWBox\controller\FolderControllers\GetFolderController')->setName('get-folder');

$this->put('/{folderID}', 'PWBox\controller\FolderControllers\UpdateFolderController')->setName('update-folder');

$this->delete('/{folderID}', 'PWBox\controller\FolderControllers\DeleteFolderController')->setName('delete-folder');

//File
$this->group('/{folderID}/file', function (){
    require __DIR__.'/apiFileRoutes.php';
});