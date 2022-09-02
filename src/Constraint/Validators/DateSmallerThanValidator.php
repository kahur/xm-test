<?php

namespace App\Constraint\Validators;

use App\Constraint\DateSmallerThan;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class DateSmallerThanValidator extends ConstraintValidator
{
    public const PATTERN = '/^(?<year>\d{4})-(?<month>\d{2})-(?<day>\d{2})$/';

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!preg_match(static::PATTERN, $value, $matches)) {
            $this->context->buildViolation(DateSmallerThan::MESSAGE_DEFAULT)
                ->setParameter('{{ value }}', $value)
                ->setCode(Date::INVALID_FORMAT_ERROR)
                ->addViolation();

            return;
        }

        $dateValue = strtotime($value);

        if (empty($constraint->dates)) {
            $constraint->dates = [new \DateTime('now')];
        }

        foreach ($constraint->dates as $date) {
            if (!$date instanceof \DateTime) {
                if (!is_string($date)) {
                    throw new \Exception('Invalid option date, use \DateTime as options for ' . get_class($constraint) . ' constraint');
                }

                // attempt to extract value from field
                $path = $date;
                $object = $this->context->getObject()->getParent();

                try {

                    $value = (isset($object[$path])) ? $object[$path]->getData() : null;
                    if (!$value) {
                        return;
                    }

                    $date = new \DateTime($value);
                } catch (NoSuchPropertyException $e) {
                    throw new ConstraintDefinitionException(sprintf('Invalid property path "%s" provided to "%s" constraint: ', $path, get_debug_type($constraint)).$e->getMessage(), 0, $e);
                }

            }

            if ($dateValue > $date->getTimestamp()) {
                $this->context->buildViolation(DateSmallerThan::MESSAGE_DATE_IS_HIGHER)
                    ->setParameter('{{ value }}', $value)
                    ->setParameter('{{ compareDate }}', $date->format('Y-m-d'))
                    ->addViolation();

                return;
            }
        }
    }
}
