<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-07
 * Time: 23:03
 */

namespace AppBundle\Utils;

use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Debug\Exception\FatalErrorException;


/**
 * Class CsvManager
 * @package AppBundle\Utils
 */
class CsvManager
{
    /**
     *
     */
    private const MIN_COLUMN_NUMBER = 2;
    /**
     *
     */
    private const MAX_COLUMN_NUMBER = 50;
    /**
     *
     */
    private const HEADER_INDEX = 0;
    /**
     *
     */
    private const CSV_ITEM_KEY = 'csv';
    /**
     * @var FilesystemAdapter
     */
    private $cacheAdapter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * CsvManager constructor.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->loggger = $logger;
        $this->cacheAdapter = new FilesystemAdapter('app.cache');
    }

    /**
     * @param string $filePath
     */
    public function parseFile(string $filePath): void
    {
        $csvParser = new CsvParser($filePath);
        $item = $this->cacheAdapter->getItem(self::CSV_ITEM_KEY);
        $this->cacheAdapter->save($item->set($csvParser->getData()));
    }

    /**
     *
     */
    public function clearCache(): void
    {
        $this->cacheAdapter->deleteItem(self::CSV_ITEM_KEY);
    }

    /**
     * @return array
     */
    private function getHeader(): array
    {
        return $this->getCsv()[self::HEADER_INDEX];
    }

    /**
     * @return array
     */
    public function getHeaderToChoice(): array
    {
        $choices = [];
        foreach ($this->getHeader() as $item) {
            $choices[$item] = $item;
        }
        asort($choices);
        return $choices;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = $this->getCsv();
        unset($data[self::HEADER_INDEX]);
        return $data;
    }

    /**
     * @return bool
     */
    public function isColumnNumberValid(): bool
    {
        $columnNumber = $this->getColumnNumber();
        return ($columnNumber >= self::MIN_COLUMN_NUMBER && $columnNumber <= self::MAX_COLUMN_NUMBER);
    }

    public function getColumnKey($columnName): int
    {
        return array_search($columnName, $this->getHeader());
    }

    /**
     * @return array
     */
    private function getCsv(): array
    {
        try {
            return $this->cacheAdapter->getItem(self::CSV_ITEM_KEY)->get();
        } catch (FatalErrorException $exception) {
            $this->logger->critical('An error occurred: ' . $exception->getMessage());
        }
    }

    /**
     * @return int
     */
    private function getColumnNumber(): int
    {
        return count($this->getHeader());
    }
}
