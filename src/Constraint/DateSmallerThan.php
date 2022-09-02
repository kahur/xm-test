<?php

namespace App\Constraint;

use App\Constraint\Validators\DateSmallerThanValidator;
use Symfony\Component\Validator\Constraint;

class DateSmallerThan extends Constraint
{
    public const MESSAGE_DEFAULT = 'The date {{value}} is not valid';
    public const MESSAGE_DATE_IS_HIGHER = 'The date {{ value }} is higher than {{ compareDate }}';

    public $message = 'The date {{ value }} is higher than {{ compareDate }}';
    public $dates = [];

    public function validatedBy(): string
    {
        return DateSmallerThanValidator::class;
    }
}
