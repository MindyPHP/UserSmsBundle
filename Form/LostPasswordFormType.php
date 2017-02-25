<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\Form;

use libphonenumber\PhoneNumberFormat;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Mindy\Bundle\UserSmsBundle\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LostPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', PhoneNumberType::class, [
                'label' => 'Номер телефона',
                'default_region' => 'RU',
                'format' => PhoneNumberFormat::NATIONAL,
                'constraints' => [
                    new Assert\Callback(function ($value, ExecutionContextInterface $context, $payload) {
                        if (User::objects()->filter(['phone' => $value->getNationalNumber()])->count() == 0) {
                            $context->buildViolation('Пользователь с таким номером телефона не зарегистрирован на сайте')
                                ->addViolation();
                        }
                    }),
                ],
                'help' => 'Введите ваш номер телефона указанный при регистрации. Вам будет отправлено SMS сообщение с кодом подтверждения.',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Отправить код',
            ]);
    }
}
