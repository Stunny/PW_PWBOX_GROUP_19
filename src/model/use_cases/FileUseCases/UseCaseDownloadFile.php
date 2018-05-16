<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 4/15/2018
 * Time: 12:57 PM
 */

namespace PWBox\model\use_cases\FileUseCases;

use PWBox\model\repositories\FileRepository;
use PWBox\model\repositories\FolderRepository;

class UseCaseDownloadFile
{
    private $fileRepository;
    private $folderRepository;

    /**
     * UseCasePostUser constructor.
     * @param FileRepository $repository
     * @param FolderRepository $folderRepository
     */
    public function __construct(FileRepository $repository, FolderRepository $folderRepository)
    {
        $this->fileRepository = $repository;
        $this->folderRepository = $folderRepository;
    }

    public function __invoke($fileId)
    {
        $file = $this->fileRepository->getData($fileId);

        if($file->getName() != null){
            $content = $this->fileRepository->download($file, $this->folderRepository->get($file->getFolder(), $file->getCreador()))->getFile();
            return $content;
        }else{
            return false;
        }
    }
}