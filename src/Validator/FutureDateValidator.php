<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FutureDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value) {
            return;
        }

        // $expirationDate = \DateTime::createFromFormat('m/y', $value);
        $now = new \DateTime();

        if ($value < $now) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
} 