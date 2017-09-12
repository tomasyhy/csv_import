<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-12
 * Time: 17:30
 */

namespace AppBundle\Entity;

/**
 * Class Report
 * @package AppBundle\Entity
 */
class Report
{
    /**
     * @var Import
     */
    private $import;

    /**
     * Report constructor.
     * @param Import $import
     */
    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    /**
     * @return string
     */
    public function generateReport(): string
    {
        return "Failed: {$this->import->getErrorsCount()}, 
        success: {$this->import->getSuccessCount()}, 
        execution time {$this->timeParse($this->import->getImportTime())}";
    }

    /**
     * @param int $microtime
     * @return string
     */
    private function timeParse(int $microtime): string
    {
        return date("H:i:s", $microtime);
    }
}