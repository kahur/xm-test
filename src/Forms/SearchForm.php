<?php

namespace App\Forms;

use App\Constraint\DateSmallerThan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'symbol',
                TextType::class,
                ['attr' => ['class' => 'autocomplete']]
            )
            ->add(
                'company_name',
                HiddenType::class,
                [
                    'attr' => ['id' => 'company_name'],
                    'constraints' => [
                        new NotBlank(['message' => 'Please select symbol'])
                    ]
                ]
            )
            ->add('company_code',
                HiddenType::class,
                [
                    'attr' => ['id' => 'company_code'],
                    'constraints' => [
                        new NotBlank(['message' => 'Please select symbol'])
                    ]
                ]
            )
            ->add(
                'start_date',
                TextType::class,
                [
                    'attr' => ['class' => 'from-datepicker'],
                    'constraints' => [
                        new DateSmallerThan([
                            'dates' => [
                                new \DateTime('now'),
                                'end_date'
                            ]
                        ])
                    ]
                ]
            )
            ->add(
                'end_date',
                TextType::class,
                [
                    'attr' => ['class' => 'to-datepicker'],
                    'constraints' => [
                        new DateSmallerThan([
                            'dates' => [
                                new \DateTime('now'),
                            ]
                        ])
                    ]
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => [
                        new Email()
                    ]
                ]
            )
            ->add('submit', SubmitType::class);
    }
}
