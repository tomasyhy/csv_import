<?php

namespace AppBundle\Utils;

/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-07
 * Time: 22:47
 */
class CsvParser
{
    private $fileArray;

    public function __construct(string $filePath)
    {
        $this->fileArray = array_map('str_getcsv', file($filePath));
    }

    public function getData()
    {
        return $this->fileArray;
    }
}