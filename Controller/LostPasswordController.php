<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\Controller;

use libphonenumber\PhoneNumber;
use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\UserSmsBundle\EventListener\UserLostPasswordEvent;
use Mindy\Bundle\UserSmsBundle\Form\ConfirmSmsFormType;
use Mindy\Bundle\UserSmsBundle\Form\LostPasswordFormType;
use Mindy\Bundle\UserSmsBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LostPasswordController extends Controller
{
    public function lostAction(Request $request)
    {
        $form = $this->createForm(LostPasswordFormType::class, [], [
            'method' => 'POST',
        ]);

        if ($form->handleRequest($request)->isValid()) {
            $data = $form->getData();

            /** @var PhoneNumber $phone */
            $phone = $data['phone'];

            /** @var User $user */
            $user = User::objects()->get([
                'phone' => $phone->getNationalNumber(),
            ]);
            $token = random_int(10000, 99999);
            $user->token = $token;
            $user->save(['token']);

            $this->get('event_dispatcher')->dispatch(
                UserLostPasswordEvent::EVENT_NAME,
                new UserLostPasswordEvent($user)
            );

            $this->addFlash('success', 'Код подтверждения отправлен на указанный номер телефона');

            return $this->redirectToRoute('user_sms_lost_password_confirm', [
                'token' => $token,
            ]);
        }

        return $this->render('user_sms/lost/form.html', [
            'form' => $form->createView(),
        ]);
    }

    public function confirmAction(Request $request, $token)
    {
        /** @var User $user */
        $user = User::objects()->get(['token' => $token]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ConfirmSmsFormType::class, [], [
            'method' => 'POST',
            'token' => $user->token,
            'action' => $this->generateUrl('user_sms_lost_password_confirm', [
                'token' => $user->token,
            ]),
        ]);

        if ($form->handleRequest($request)->isValid()) {
            return $this->redirectToRoute('user_sms_set_password', [
                'token' => $user->token,
            ]);
        }

        return $this->render('user_sms/lost/confirm.html', [
            'form' => $form->createView(),
        ]);
    }
}
