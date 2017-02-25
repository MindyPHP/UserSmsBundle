<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $min = 6;
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Пароли не совпадают',
                'required' => true,
                'first_options' => [
                    'label' => 'Пароль',
                    'help' => sprintf('Пароль должен содержать не менее %s символов', $min),
                ],
                'second_options' => [
                    'label' => 'Повтор пароля',
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => $min,
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Создать пароль и войти',
            ]);
    }
}
