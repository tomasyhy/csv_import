<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-07
 * Time: 12:04
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{FileType, SubmitType};
use Symfony\Component\Validator\Constraints\File as FileConstraints;

class File extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, array('label' => 'CSV File'))
            ->add('save', SubmitType::class, array('label' => 'Upload file'))
        ;
    }
}