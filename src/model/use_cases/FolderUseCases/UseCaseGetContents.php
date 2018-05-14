<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 6/5/18
 * Time: 16:36
 */

namespace PWBox\model\use_cases\FolderUseCases;

use PWBox\model\Folder;
use PWBox\model\repositories\FileRepository;
use PWBox\model\repositories\FolderRepository;

class UseCaseGetContents
{

    private const FOLDERS_DIR = "/home/vagrant/pwbox/appdata/user_folders/";

    private $repository;

    function __construct(FolderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $urlArgs)
    {
        // TODO: Implement __invoke() method.
    }

}