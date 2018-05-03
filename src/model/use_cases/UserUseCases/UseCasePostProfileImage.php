<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18/4/18
 * Time: 11:59
 */

namespace PWBox\model\use_cases\UserUseCases;

use PWBox\model\repositories\FileRepository;
use Slim\Http\UploadedFile;

class UseCasePostProfileImage
{

    private const PROFILE_IMGS_DIR = "/home/public/pwbox/appdata/profile_imgs/";

    private $repository;

    /**
     * UseCasePostUser constructor.
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UploadedFile $profileImg, $userName)
    {
        $extension = pathinfo($profileImg->getClientFilename(), PATHINFO_EXTENSION);
        $basename = $userName;
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $imgPath = self::PROFILE_IMGS_DIR . DIRECTORY_SEPARATOR . $filename;

        $profileImg->moveTo($imgPath);

        return $imgPath;
    }

}