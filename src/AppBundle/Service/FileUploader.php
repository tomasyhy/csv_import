<?php

/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-07
 * Time: 12:20
 */
namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        return $file->move($this->getTargetDir(), $fileName);
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}