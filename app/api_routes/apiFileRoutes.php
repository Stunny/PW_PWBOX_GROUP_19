<?php
/**
 * Created by PhpStorm.
 * File: Alex
 * Date: 4/10/2018
 * Time: 11:36 PM
 */

$this->get('/{id}', PWBox\controller\FileControllers\GetFileController::class)->setName('get-file');

$this->put('/{id}', PWBox\controller\FileControllers\PutFileController::class)->setName('update-file');

$this->delete('/{id}', PWBox\controller\FileControllers\DeleteFileController::class)->setName('delete-file');

//-----------------------------------Upload and Download

$this->get('/{id}/download', PWBox\controller\FileControllers\DownloadFileController::class)->setName('download-file');

$this->post('/upload', PWBox\controller\FileControllers\PostFileController::class)->setName('upload-file');
