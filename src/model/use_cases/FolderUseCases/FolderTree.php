<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/5/18
 * Time: 13:05
 */

namespace PWBox\model\use_cases\FolderUseCases;


class FolderTree
{
    private $id;
    private $name;
    private $children;

    public function __construct($name, $id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->children = array();
    }

    /**
     * @param FolderTree $child
     */
    public function addChild(FolderTree $child)
    {
        array_push($this->children, $child);
    }

    public function toArray(){

        $array = [];
        $this->preOrder($this, $array);
        return $array;
    }

    private function preOrder(FolderTree $root, array & $node){
        $node['name'] = $root->getName();
        $node['id'] = $root->getId();

        $children = $root->getChildren();
        $qChildren = count($children);
        $node['children'] = array_fill(0, $qChildren, []);

        for ($i = 0; $i < $qChildren; $i++){
            $this->preOrder($children[$i], $node['children'][$i]);
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }



    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

}