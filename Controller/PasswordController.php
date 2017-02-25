<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\Controller;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\UserSmsBundle\Form\ChangePasswordFormType;
use Mindy\Bundle\UserSmsBundle\Form\SetPasswordFormType;
use Mindy\Bundle\UserSmsBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class PasswordController extends Controller
{
    public function setAction(Request $request, $token)
    {
        $user = User::objects()->get(['token' => $token]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(SetPasswordFormType::class, [], [
            'method' => 'POST',
        ]);

        if ($form->handleRequest($request)->isValid()) {
            $data = $form->getData();

            $user->salt = substr(md5(time().$user->phone), 0, 10);
            $user->password = $this->get('security.password_encoder')->encodePassword($user, $data['password']);
            $user->token = null;

            if (false === $user->save()) {
                throw new \RuntimeException('Failed to set user password');
            }

            // Here, $providerKey is the name of the firewall in your security.yml
            $providerKey = 'site';

            $token = new UsernamePasswordToken($user, $user->getPassword(), $providerKey, $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            $session = $request->getSession();
            $session->set(sprintf('_security_%s', $providerKey), serialize($token));
            $session->save();

            // Fire the login event
            // Logging the user in above the way we do it doesn't do this automatically
            $event = new InteractiveLoginEvent($request, $token);
            $this->get('event_dispatcher')->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event);

            return $this->redirect('/');
        }

        return $this->render('user_sms/password/set.html', [
            'form' => $form->createView(),
        ]);
    }

    public function changeAction(Request $request)
    {
        if (false === $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->createAccessDeniedException();
        }

        $form = $this->createForm(ChangePasswordFormType::class, [], [
            'method' => 'POST',
        ]);

        $user = $this->getUser();
        if ($form->handleRequest($request)->isValid()) {
            $data = $form->getData();

            $user->salt = substr(md5(time().$user->phone), 0, 10);
            $user->password = $this->get('security.password_encoder')->encodePassword($user, $data['password']);
            $user->token = null;

            if (false === $user->save()) {
                throw new \RuntimeException('Failed to set user password');
            }

            return $this->redirect('/');
        }

        return $this->render('user_sms/password/change.html', [
            'form' => $form->createView(),
        ]);
    }
}
