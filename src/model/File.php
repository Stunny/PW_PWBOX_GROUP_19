<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 6:05 PM
 */

namespace PWBox\model;

class File
{
  private $id;
  private $name;
  private $creador;
  private $folder;
  private $created_at;
  private $updated_at;

  private $file;

  public function __construct(
    $id,
    $name,
    $creador,
    $folder,
    $created_at,
    $updated_at,
    $file){

      $this->id = $id;
      $this->name = $name;
      $this->folder = $folder;
      $this->creador = $creador;
      $this->created_at = $created_at;
      $this->updated_at = $updated_at;
      $this->file = $file;
  }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }


    /**
     * Set the value of Created by PhpStorm.
     *
     * @param mixed id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of Created by PhpStorm.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Name
     *
     * @param mixed name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of Name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Creador
     *
     * @param mixed creador
     *
     * @return self
     */
    public function setCreador($creador)
    {
        $this->creador = $creador;

        return $this;
    }

    /**
     * Get the value of Creador
     *
     * @return mixed
     */
    public function getCreador()
    {
        return $this->creador;
    }

    /**
     * Set the value of Folder
     *
     * @param mixed folder
     *
     * @return self
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get the value of Folder
     *
     * @return mixed
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set the value of Created At
     *
     * @param mixed created_at
     *
     * @return self
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of Created At
     *
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set the value of Updated At
     *
     * @param mixed updated_at
     *
     * @return self
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of Updated At
     *
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}
