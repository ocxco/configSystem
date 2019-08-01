<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%namespace}}`.
 */
class m190606_090955_create_namespace_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('namespace', [
            'id' => $this->primaryKey(),
            'namespace' => $this->string(16)->notNull()->defaultValue('')->comment('命名空间'),
            'create_time' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('创建时间'),
        ], "ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '配置命名空间表'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('namespace');
    }
}
