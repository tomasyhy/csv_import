<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-07
 * Time: 09:10
 */
class File
{
    /**
     * @var
     */
    protected $file;

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('file', new Assert\File(array(
            'maxSize' => '1024k',
            'mimeTypes' => array(
                'text/plain',
                'text/csv',
            ),
            'mimeTypesMessage' => 'Please upload a valid CSV',
        )));
    }
}