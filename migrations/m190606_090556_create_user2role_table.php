<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user2role`.
 */
class m190606_090556_create_user2role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user2role', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->defaultValue(0)->comment('userId'),
            'role_id' => $this->integer()->notNull()->defaultValue(0)->comment('roleId'),
            'create_time' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('创建时间'),
        ], "ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '用户角色分配'");
        $this->createIndex('idx_uid', 'user2role', ['user_id']);
        $this->createIndex('idx_rid', 'user2role', ['role_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user2role');
    }
}
