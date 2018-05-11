<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/5/18
 * Time: 12:25
 */

namespace PWBox\model\use_cases\FolderUseCases;

use PWBox\model\Folder;
use PWBox\model\repositories\FolderRepository;

class UseCaseGetFolderTree
{

    private const FOLDERS_DIR = "/home/vagrant/pwbox/appdata/user_folders/";

    private $repository;

    function __construct(FolderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $urlArgs)
    {
        $folder = $this->repository->get($urlArgs['folderID'], $urlArgs['userID']);

        if($folder == null){
            return null;
        }

        $tree = new FolderTree($folder->getNom());
        $this->buildTree($folder->getPath(), $tree);

        return $tree->toArray();

    }

    private function buildTree(string $path, FolderTree $tree){
        $content = scandir(self::FOLDERS_DIR . $path);

        foreach ($content as $item){
            if($item == "." || $item == "..")
                continue;

            $subcontent = scandir(self::FOLDERS_DIR . $path . '/' . $item);

            if($subcontent != false){
                $child = new FolderTree($item);
                $tree->addChild($child);

                $this->buildTree($path . '/' . $item, $child);
            }
        }
    }
}