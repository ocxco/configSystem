<?php

use yii\db\Migration;

/**
 * Handles the creation of table `role2namespace`.
 */
class m190606_091628_create_role2namespace_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('role2namespace', [
            'id' => $this->primaryKey(),
            'role_id' => $this->integer()->notNull()->defaultValue(0)->comment('roleId'),
            'namespace_id' => $this->integer()->notNull()->defaultValue(0)->comment('namespaceId'),
            'create_time' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('创建时间'),
        ], "ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '角色的命名空间权限'");
        $this->createIndex('idx_rid', 'role2namespace', ['role_id']);
        $this->createIndex('idx_nid', 'role2namespace', ['namespace_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('role2namespace');
    }
}
