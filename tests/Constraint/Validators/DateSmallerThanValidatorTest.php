<?php

namespace App\Tests\Constraint\Validators;

use App\Constraint\DateSmallerThan;
use App\Constraint\Validators\DateSmallerThanValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use Symfony\Component\Validator\Constraints\Date;

class DateSmallerThanValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
    {
        return new DateSmallerThanValidator();
    }

    public function testInvalidDate()
    {
        $this->validator->validate('abcd', new DateSmallerThan());

        $this->buildViolation(DateSmallerThan::MESSAGE_DEFAULT)
            ->setParameter('{{ value }}', 'abcd')
            ->setCode(Date::INVALID_FORMAT_ERROR)
            ->assertRaised();

    }

    public function testDateHigherThan()
    {
        $higherDate = '2022-09-02';
        $compareDate = '2022-09-01';
        $this->validator->validate($higherDate, new DateSmallerThan(
            [
                'dates' => [
                    new \DateTime($compareDate)
                ]
            ]
        ));

        $this->buildViolation(DateSmallerThan::MESSAGE_DATE_IS_HIGHER)
            ->setParameter('{{ value }}', $higherDate)
            ->setParameter('{{ compareDate }}', $compareDate)
            ->assertRaised();
    }
}
