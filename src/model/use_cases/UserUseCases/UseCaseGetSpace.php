<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20/5/18
 * Time: 19:34
 */

namespace PWBox\model\use_cases\UserUseCases;


use PWBox\model\User;
use PWBox\model\repositories\UserRepository;

class UseCaseGetSpace
{
    private const USER_FOLDERS_DIR = "/home/vagrant/pwbox/appdata/user_folders/";
    private $repository;

    /**
     * UseCasePostUser constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($userId)
    {
        $username = $this->repository->get($userId)['username'];

        $f = self::USER_FOLDERS_DIR.$username;
        $io = popen ( '/usr/bin/du -sk ' . $f, 'r' );
        $size = fgets ( $io, 4096);
        $currentFolderSize = intval(substr ( $size, 0, strpos ( $size, "\t" )));

        return ["space" => $currentFolderSize];
    }
}