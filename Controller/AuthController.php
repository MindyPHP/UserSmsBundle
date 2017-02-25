<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\Controller;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\UserSmsBundle\Form\AuthForm;
use Mindy\Bundle\UserSmsBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends Controller
{
    public function loginAction(Request $request, User $user = null)
    {
        if ($user && $user->is_active) {
            return $this->redirect('/');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        $form = $this->createForm(AuthForm::class, [], [
            'method' => 'POST',
            'action' => $this->generateUrl('user_sms_login'),
        ]);

        return $this->render('user_sms/auth/login.html', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'form' => $form->createView(),
        ]);
    }

    public function logoutAction()
    {
        return $this->redirect('/');
    }
}
