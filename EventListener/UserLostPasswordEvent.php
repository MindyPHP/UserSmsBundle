<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserSmsBundle\EventListener;

use Mindy\Bundle\UserSmsBundle\Model\User;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class UserLostPasswordEvent
 * @package Mindy\Bundle\UserSmsBundle\EventListener
 */
class UserLostPasswordEvent extends Event
{
    const EVENT_NAME = 'user.lost_password';

    /**
     * @var User
     */
    protected $user;

    /**
     * UserLostPasswordEvent constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
