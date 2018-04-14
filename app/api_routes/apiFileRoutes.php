<?php
/**
 * Created by PhpStorm.
 * File: Alex
 * Date: 4/10/2018
 * Time: 11:36 PM
 */

$this->get('/{id}', PWBox\controller\FileController::class,':get')->setName('get-file');

$this->put('/{id}', PWBox\controller\FileController::class,':put')->setName('update-file');

$this->delete('/{id}', PWBox\controller\FileController::class,':delete')->setName('delete-file');

//-----------------------------------Upload and Download

$this->get('/{id}/download', PWBox\controller\FileController::class,':download')->setName('download-file');

$this->get('/{id}/upload', PWBox\controller\FileController::class,':upload')->setName('upload-file');
