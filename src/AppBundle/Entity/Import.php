<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-12
 * Time: 13:37
 */

namespace AppBundle\Entity;


use AppBundle\Utils\CsvManager;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;

/**
 * Class Import
 * @package AppBundle\Entity
 */
class Import
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var array
     */
    private $mapping;
    /**
     * @var CsvManager
     */
    private $csvManager;
    /**
     * @var int
     */
    private $errorsCount = 0;
    /**
     * @var int
     */
    private $successCount = 0;
    /**
     * @var int
     */
    private $importTime = 0;

    /**
     * @return int
     */
    public function getErrorsCount(): int
    {
        return $this->errorsCount;
    }

    /**
     * @return int
     */
    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    /**
     * @return int
     */
    public function getImportTime(): int
    {
        return $this->importTime;
    }

    /**
     * Import constructor.
     * @param array $mapping
     * @param Connection $dbalConnection
     * @param LoggerInterface $logger
     */
    public function __construct(array $mapping, Connection $dbalConnection, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->connection = $dbalConnection;
        $this->mapping = $mapping;
        $this->csvManager = new CsvManager($logger);
    }

    /**
     *
     */
    public function run(): void
    {
        $startTime = microtime(true);
        foreach ($this->csvManager->getData() as $csvRow) {
            $this->saveRow($csvRow);
        }
        $endTime = microtime(true);
        $this->importTime = $endTime - $startTime;
    }

    /**
     * @param array $csvRow
     */
    private function saveRow(array $csvRow): void
    {
        try {
            $this->connection->insert('User', $this->prepareRowData($csvRow));
            $this->successCount++;
        } catch (DBALException $exception) {
            $this->errorsCount++;
            $this->logger->critical('An error occurred: ' . $exception->getMessage());
        }
    }

    /**
     * @param array $csvRow
     * @return array
     */
    private function prepareRowData(array $csvRow)
    {
        $columnValues = [];
        foreach ($this->mapping as $dbColumn => $csvColumn) {
            if ($this->columnMapped($csvColumn)) {
                $columnValues[$dbColumn] = $this->getCsvValue($csvColumn, $csvRow);
            }
        }
        return $columnValues;
    }

    /**
     * @param $csvColumn
     * @return bool
     */
    private function columnMapped($csvColumn): bool
    {
        return !empty($csvColumn);
    }

    /**
     * @param string $csvColumn
     * @param $csvRow
     * @return mixed
     */
    private function getCsvValue(string $csvColumn, $csvRow)
    {
        $key = $this->csvManager->getColumnKey($csvColumn);
        return $csvRow[$key];
    }


}