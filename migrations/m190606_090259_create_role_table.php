<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%role}}`.
 */
class m190606_090259_create_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('role', [
            'id' => $this->primaryKey(),
            'name' => $this->string(16)->notNull()->defaultValue('')->comment('roleName'),
            'create_time' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('创建时间'),
        ], "ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '用户角色表'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('role');
    }
}
