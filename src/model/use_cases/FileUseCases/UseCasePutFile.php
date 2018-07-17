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
        $folder = $this->folderRepo->get($args['folderID'], $args['userID']);
        if($folder->getId() != null){

            $file = $this->fileRepo->getData(new File($args['fileID'], null, $folder->getCreador(), $args['folderID'], null, null, null));
            $return = $this->fileRepo->getData(new File($args['fileID'], null, $folder->getCreador(), $args['folderID'], null, null, null));

            if (200 == $this->fileRepo->canEdit($folder->getId(), $folder->getPath(), $args['userID'], $folderID = (int)$this->folderRepo->getByPath($folder->getPath())['id'])){
                //rename file
                $this->fileRepo->updateData($folder, $args['userID'], $rawData['filename'], $args['fileID']);
                $path = $this->folderRepo->get($folder->getId(), $args['userID'])->getPath();
                $ext = pathinfo($file->getName());
                rename("/home/vagrant/pwbox/appdata/user_folders/" . $path . DIRECTORY_SEPARATOR . $file->getName(), "/home/vagrant/pwbox/appdata/user_folders/" . $path . DIRECTORY_SEPARATOR . $rawData['filename'] . '.' . $ext['extension']);
                return 200;
            }else{
                return 401;
            }
        }else{
            return 404;
        }
    }

}