<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\EventListener;

use Mindy\Bundle\SmsBundle\Pool\SmsSpool;

class UserListener
{
    protected $smsSpool;

    public function __construct(SmsSpool $smsSpool)
    {
        $this->smsSpool = $smsSpool;
    }

    public function onUserLostPassword(UserLostPasswordEvent $event)
    {
        $user = $event->getUser();

        $this->smsSpool->create($user->phone, sprintf(
            'Код для восстановления пароля: %s',
            $user->token
        ));
    }

    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $user = $event->getUser();

        $this->smsSpool->create($user->phone, sprintf(
            'Код подтверждения регистрации: %s',
            $user->token
        ));
    }
}
