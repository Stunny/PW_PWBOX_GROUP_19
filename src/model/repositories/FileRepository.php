<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:04 PM
 */

namespace PWBox\model\repositories;

use PWBox\model\File;

interface FileRepository
{
    public function post(File $file);

    public function download(File $file);

    public function delete(File $file);

    public function getData(File $file);

    public function updateData(File $file);

}