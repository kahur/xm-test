<?php

namespace App\Tests\Forms;

use App\Forms\SearchForm;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class SearchFormTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator)
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'company_code' => 'AAB',
            'company_name' => 'test',
            'email' => 'test@test.com',
            'start_date' => '2022-09-02',
            'end_date' => '2022-09-01'
        ];

        $form = $this->factory->create(SearchForm::class, []);

        $form->submit($formData);
        $this->assertTrue($form->isValid());
    }

    public function testSubmitInvalidDateData()
    {
        $formData = [
            'company_code' => 'AAB',
            'company_name' => 'test',
            'email' => 'test@test.com',
            'start_date' => '2022-09-02',
            'end_date' => '2022-09-03'
        ];

        $form = $this->factory->create(SearchForm::class, []);

        $form->submit($formData);
        $this->assertFalse($form->isValid());
    }
}
