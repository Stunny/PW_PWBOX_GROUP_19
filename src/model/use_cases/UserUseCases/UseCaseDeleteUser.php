<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/10/2018
 * Time: 11:35 AM
 */

namespace PWBox\model\use_cases\UserUseCases;

use PWBox\model\User;
use PWBox\model\repositories\UserRepository;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class UseCaseDeleteUser
{
    private $repository;

    /**
     * UseCasePostUser constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($user)
    {

        if($user != null){
            $this->repository->delete($user);
            unlink("/home/vagrant/pwbox/public/profile_imgs/" . $user['username'] . ".jpg");
            $dir = "/home/vagrant/pwbox/appdata/user_folders/" . $user['username'];

            $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it,
                RecursiveIteratorIterator::CHILD_FIRST);
            foreach($files as $file) {
                if ($file->isDir()){
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);
            return true;
        }else{
            return false;
        }
    }
}
