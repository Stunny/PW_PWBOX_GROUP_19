<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/15/2018
 * Time: 12:57 PM
 */

namespace PWBox\model\use_cases\FileUseCases;

use Doctrine\DBAL\VersionAwarePlatformDriver;
use PWBox\model\File;
use PWBox\model\repositories\FileRepository;
use PWBox\model\repositories\FolderRepository;

class UseCasePutFile
{

    private $fileRepo;
    private $folderRepo;

    /**
     * UseCasePostUser constructor.
     * @param FileRepository $repository
     * @param FolderRepository $folderRepository
     */
    public function __construct(FileRepository $repository, FolderRepository $folderRepository)
    {
        $this->fileRepo = $repository;
        $this->folderRepo = $folderRepository;
    }

    public function __invoke(array $rawData, array $args): bool
    {
        $file = $this->fileRepo->getData(new File($args['fileID'], null, $args['userID'], $args['folderID'], null, null, null));
        if($file->getId() != null){
            $this->fileRepo->updateData($file, $args['userID'], $rawData['filename']);
            $return = $this->fileRepo->getData(new File($args['fileID'], null, $args['userID'], $args['folderID'], null, null, null));
            if ($return->getId() != null){
                //rename file
                $path = $this->folderRepo->get($file->getFolder(), $args['userID'])->getPath();
                rename("/home/vagrant/pwbox/appdata/user_folders/" . $path . DIRECTORY_SEPARATOR . $file->getName(), "/home/vagrant/pwbox/appdata/user_folders/" . $path . DIRECTORY_SEPARATOR . $return->getName());
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}