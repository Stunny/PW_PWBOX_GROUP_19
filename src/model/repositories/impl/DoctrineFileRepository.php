<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/14/2018
 * Time: 9:40 PM
 */

namespace PWBox\model\repositories\impl;


use PWBox\model\File;
use PWBox\model\repositories\FileRepository;

class DoctrineFileRepository implements FileRepository
{

    public function post(File $file): File
    {
        /* TODO: Implement post() method. Una vez cargado el archivo en la carpeta del usuario y sus datos en la base
         *   de datos, devolver un objeto de clase File el cual contenga la ID del archivo en la BD
        */
    }

    public function download(File $file): File
    {
        // TODO: Implement download() method. Devolver un objeto de la clase File cuyo atributo `file` contenga el archivo
    }

    public function delete(File $file)
    {
        // TODO: Implement delete() method. Eliminar archivo del sistema y de la base de datos
    }

    public function getData(File $file): File
    {
        // TODO: Implement getData() method. Devolver un objeto de la clase File con su stributo `file` a null
    }

    public function updateData(File $file): File
    {
        // TODO: Implement updateData() method. Devolver el objeto de la clase File con los nuevos datos y con su atributo `file` a null
    }
}