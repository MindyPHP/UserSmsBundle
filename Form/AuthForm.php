<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\Form;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AuthForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', PhoneNumberType::class, [
                'label' => 'Номер телефона',
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'Пароль',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Вход',
            ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
