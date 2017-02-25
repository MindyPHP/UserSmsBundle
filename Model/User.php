<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * Author: Maxim Falaleev <max@studio107.ru>
 */

namespace Mindy\Bundle\UserSmsBundle\Model;

use Mindy\Orm\Fields\BooleanField;
use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\EmailField;
use Mindy\Orm\Model;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User.
 *
 * @property string $phone
 * @property string $email
 * @property string $password
 * @property string $token
 * @property bool $is_active
 * @property string $salt
 */
class User extends Model implements UserInterface
{
    public static function getFields()
    {
        return [
            'phone' => [
                'class' => CharField::class,
            ],
            'email' => [
                'class' => EmailField::class,
                'null' => true,
            ],
            'password' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'token' => [
                'class' => CharField::class,
                'null' => true,
            ],
            'is_active' => [
                'class' => BooleanField::class,
                'default' => false,
            ],
            'salt' => [
                'class' => CharField::class,
                'null' => true,
            ],
        ];
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_MEMBER'];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->phone;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->password = null;
    }

    public function __toString()
    {
        return (string) $this->phone;
    }
}
