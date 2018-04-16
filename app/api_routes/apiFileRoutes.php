<?php
/**
 * Created by PhpStorm.
 * File: Alex
 * Date: 4/10/2018
 * Time: 11:36 PM
 */

$this->get('/{fileID}', PWBox\controller\FileControllers\GetFileController::class)->setName('get-file');

$this->put('/{fileID}', PWBox\controller\FileControllers\PutFileController::class)->setName('update-file');

$this->delete('/{fileID}', PWBox\controller\FileControllers\DeleteFileController::class)->setName('delete-file');

//-----------------------------------Upload and Download

$this->get('/{fileID}/download', PWBox\controller\FileControllers\DownloadFileController::class)->setName('download-file');

$this->post('', PWBox\controller\FileControllers\PostFileController::class)->setName('upload-file');
