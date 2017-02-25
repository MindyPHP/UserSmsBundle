<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ConfirmSmsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $token = $options['token'];
        $builder
            ->add('token', TextType::class, [
                'label' => 'Код подтверждения',
                'constraints' => [
                    new Assert\Callback(function ($value, ExecutionContextInterface $context, $payload) use ($token) {
                        if ($value != $token) {
                            $context->buildViolation('Не корректный код подтверждения')
                                ->addViolation();
                        }
                    }),
                ],
                'help' => 'Введите код полученный по SMS',
                'tooltip' => 'Смс сообщение может придти в течении 1-5 минут. Пожалуйста ожидайте.',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Подтвердить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'token' => null,
        ]);
    }
}
