<?php

/*
 * (c) Studio107 <mail@studio107.ru> http://studio107.ru
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * Author: Maxim Falaleev <max@studio107.ru>
 */

namespace Mindy\Bundle\UserSmsBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Mindy\Bundle\UserSmsBundle\Model\User;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170201175610 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $userTable = $schema->createTable(User::tableName());
        $userTable->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $userTable->addColumn('phone', 'string', ['length' => 11]);
        $userTable->addColumn('email', 'string', ['length' => 150, 'notnull' => false]);
        $userTable->addColumn('password', 'string', ['length' => 255, 'notnull' => false]);
        $userTable->addColumn('salt', 'string', ['length' => 255, 'notnull' => false]);
        $userTable->addColumn('token', 'string', ['length' => 255, 'notnull' => false]);
        $userTable->addColumn('is_active', 'boolean', ['default' => 0]);
        $userTable->setPrimaryKey(['id']);
        $userTable->addUniqueIndex(['phone'], 'phone_uniq');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(User::tableName());
    }
}
