<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\Controller;

use Mindy\Bundle\MindyBundle\Controller\Controller;

class ProfileController extends Controller
{
    public function viewAction()
    {
        $user = $this->getUser();

        return $this->render('user_sms/profile/view.html');
    }
}
