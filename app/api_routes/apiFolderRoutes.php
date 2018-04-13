<?php
/**
 * Created by PhpStorm.
 * Folder: Alex
 * Date: 4/10/2018
 * Time: 11:36 PM
 */

$this->post('/', 'PWBox\controller\FolderControllers\PostFolderController')->setName('post-folder');

$this->get('/{id}', 'PWBox\controller\FolderControllers\GetFolderController')->setName('get-folder');

$this->put('/{id}', 'PWBox\controller\FolderControllers\UpdateFolderController')->setName('update-folder');

$this->delete('/{id}', 'PWBox\controller\FolderControllers\DeleteFolderController')->setName('delete-folder');
