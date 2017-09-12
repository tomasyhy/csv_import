<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;

/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-11
 * Time: 13:56
 */
class CreateTableUser extends Command
{
    private const SQL_DIR = __DIR__ . '/../../../app/user.sql';
    private $logger;
    private $connection;

    public function __construct(Connection $dbalConnection, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->connection = $dbalConnection;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:create-table-user')
            ->setDescription('Creates a new user tab;e.')
            ->setHelp('This command create user table defined in user.sql file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->connection->executeQuery(file_get_contents(self::SQL_DIR));
        } catch (\Exception $e) {
            $this->logger->critical('Required SQL file doesn\'t exists');
        }
    }


}