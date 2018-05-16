<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/15/2018
 * Time: 12:57 PM
 */

namespace PWBox\model\use_cases\FileUseCases;


use PWBox\model\File;
use PWBox\model\repositories\FileRepository;
use PWBox\model\repositories\FolderRepository;
use Slim\Http\UploadedFile;

class UseCaseUploadFile
{
    private $fileRepository;
    private $folderRepository;

    private const USER_FOLDERS_DIR = "/home/vagrant/pwbox/appdata/user_folders/";


    /**
     * UseCasePostUser constructor.
     * @param FileRepository $fileRepository
     * @param FolderRepository $folderRepository
     */
    public function __construct(FileRepository $fileRepository, FolderRepository $folderRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->folderRepository = $folderRepository;
    }

    /**
     * @param array $uploadedFiles
     * @param int $userID
     * @param int $folderID
     * @return int
     */
    public function __invoke(array $uploadedFiles, int $userID, int $folderID)
    {

        foreach($uploadedFiles as $file){
            if ($this->fileRepository->post($userID, $folderID, $file)){
                $file->moveTo(self::USER_FOLDERS_DIR . $this->folderRepository->get($folderID, $userID)->getPath() . DIRECTORY_SEPARATOR . $file->getClientFilename());
            }
        }
    }
}