<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-11
 * Time: 11:50
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class User
{
    protected $file;

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

}