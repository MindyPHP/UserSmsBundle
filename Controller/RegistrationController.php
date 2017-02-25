<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\Controller;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\UserSmsBundle\EventListener\UserRegisteredEvent;
use Mindy\Bundle\UserSmsBundle\Form\ConfirmSmsFormType;
use Mindy\Bundle\UserSmsBundle\Form\RegistrationFormType;
use Mindy\Bundle\UserSmsBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrationController extends Controller
{
    public function registrationAction(Request $request)
    {
        $form = $this->createForm(RegistrationFormType::class, [], [
            'method' => 'POST',
            'action' => $this->generateUrl('user_sms_registration')
        ]);

        if ($request->getMethod() === 'POST') {
            if ($form->handleRequest($request)->isValid()) {
                $data = $form->getData();

                $phone = $data['phone']->getNationalNumber();
                $token = random_int(10000, 99999);

                $user = new User([
                    'phone' => $phone,
                    'token' => $token,
                ]);

                if (false == $user->save()) {
                    throw new \RuntimeException('Failed to save user');
                }

                $this->get('event_dispatcher')->dispatch(
                    UserRegisteredEvent::EVENT_NAME,
                    new UserRegisteredEvent($user)
                );

                return $this->redirectToRoute('user_sms_registration_sms_confirm', [
                    'phone' => $phone,
                ]);
            }
        }

        return $this->render('user_sms/registration/registration.html', [
            'form' => $form->createView(),
        ]);
    }

    public function confirmAction(Request $request, $phone)
    {
        /** @var User $user */
        $user = User::objects()->get(['phone' => $phone]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        if ($user->is_active) {
            $this->addFlash('success', 'Учетная запись уже активирована');

            return $this->redirect('/');
        }

        $form = $this->createForm(ConfirmSmsFormType::class, [], [
            'method' => 'POST',
            'token' => $user->token,
            'action' => $this->generateUrl('user_sms_registration_sms_confirm', [
                'phone' => $phone
            ])
        ]);

        if ($form->handleRequest($request)->isValid()) {
            $user->is_active = true;
            $user->save(['is_active']);

            return $this->redirectToRoute('user_sms_set_password', [
                'token' => $user->token,
            ]);
        }

        return $this->render('user_sms/registration/sms_confirm.html', [
            'form' => $form->createView(),
        ]);
    }
}
