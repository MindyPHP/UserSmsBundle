<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\Form;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Mindy\Bundle\UserSmsBundle\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', PhoneNumberType::class, [
                'label' => 'Номер телефона',
                'default_region' => 'RU',
                'format' => PhoneNumberFormat::NATIONAL,
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Callback(function ($value, ExecutionContextInterface $context, $payload) {
                        if (($value instanceof PhoneNumber) === false) {
                            $context->buildViolation('Не корректный номер телефона')
                                ->addViolation();
                        } elseif (User::objects()->filter(['phone' => $value->getNationalNumber()])->count() > 0) {
                            $context->buildViolation('Пользователь с таким номером телефона уже зарегистрирован на сайте')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Подтвердить номер',
                'attr' => ['class' => 'b-button'],
            ]);
    }

    protected function validate($phone)
    {
        return preg_match('/^[0-9]{10,10}+$/', $phone);
    }
}
