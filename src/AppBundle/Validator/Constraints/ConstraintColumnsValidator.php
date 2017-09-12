<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 2017-09-07
 * Time: 14:29
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConstraintColumnsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        var_dump($value); die;
        if (!preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}