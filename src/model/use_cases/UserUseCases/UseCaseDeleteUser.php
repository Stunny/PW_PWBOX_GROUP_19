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

            $files = glob('/home/vagrant/pwbox/public/profile_imgs/*', GLOB_BRACE);
            foreach($files as $file) {
                if (pathinfo($file)['filename'] == $user['username']){
                    unlink("/home/vagrant/pwbox/public/profile_imgs/" . pathinfo($file)['basename']);
                }
            }
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
