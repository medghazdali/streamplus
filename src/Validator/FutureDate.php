<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class FutureDate extends Constraint
{
    public string $message = 'The expiration date must be in the future.';
} 