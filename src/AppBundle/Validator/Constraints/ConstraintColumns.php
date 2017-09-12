<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-07
 * Time: 14:25
 */
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintColumns extends Constraint
{
    public $message = 'The file should have from 2 to 5 column';
}