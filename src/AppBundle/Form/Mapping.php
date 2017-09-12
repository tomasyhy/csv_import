<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-07
 * Time: 12:05
 */

namespace AppBundle\Form;

use AppBundle\Utils\CsvManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{
    FileType, SubmitType, ChoiceType
};
use Doctrine\DBAL\Connection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Psr\Log\LoggerInterface;


class Mapping extends AbstractType
{
    private $logger;
    private $connection;

    public function __construct(Connection $dbalConnection, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->connection = $dbalConnection;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'userTable' => null,
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $columns = $this->connection->fetchAll('SHOW COLUMNS FROM ' . $options['userTable']);
        $csvManager = new CsvManager($this->logger);
        $i = 0;
        foreach ($columns as $column) {
            $builder->add($column['Field'], ChoiceType::class, ['data' => null, 'label' => $this->dashesToCamelCase($column['Field']), 'choices' => $csvManager->getHeaderToChoice(), 'placeholder' => 'Choose CSV column', 'required' => $this->checkRequired($column)]);
            $i++;
        }
        $builder->add('Import', SubmitType::class, ['label' => 'Import']);
    }

    private function dashesToCamelCase($string)
    {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
        return $str;
    }

    private function checkRequired(array $column): bool {
        return ($column['Null'] === 'NO');
    }

}