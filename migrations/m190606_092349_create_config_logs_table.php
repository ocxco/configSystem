<?php

use yii\db\Migration;

/**
 * Handles the creation of table `config_logs`.
 */
class m190606_092349_create_config_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('config_logs', [
            'id' => $this->primaryKey()->comment('用户ID，自增'),
            'user_id' => $this->integer()->notNull()->defaultValue(0)->comment('操作用户ID'),
            'config_id' => $this->integer()->notNull()->defaultValue(0)->comment('配置ID'),
            'key' => $this->string(64)->notNull()->defaultValue('')->unique()->comment('配置项key'),
            'type' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('配置类型: 0=> 字符串, 1 => 数组, 2 => BOOL, 3 => INT'),
            'version' => $this->integer()->notNull()->defaultValue(1)->comment('配置的版本,每修改一次增加1'),
            'value' => $this->text()->comment('配置项value'),
            'create_time' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('生成时间')
        ], "ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '配置表'");
        $this->createIndex('idx_ctime', 'config_logs', ['create_time']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('config_logs');
    }
}
