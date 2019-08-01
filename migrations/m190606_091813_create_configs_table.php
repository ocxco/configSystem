<?php

use yii\db\Migration;

/**
 * Handles the creation of table `configs`.
 */
class m190606_091813_create_configs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('configs', [
            'id' => $this->primaryKey()->comment('用户ID，自增'),
            'namespace_id' => $this->integer()->notNull()->defaultValue(0)->comment('命名空间ID'),
            'key' => $this->string(64)->notNull()->defaultValue('')->comment('配置项key'),
            'name' => $this->string(64)->notNull()->defaultValue('')->comment('配置项名称'),
            'type' => $this->tinyInteger()->notNull()->defaultValue(0)->comment('配置类型: 0=> 字符串, 1 => 数组, 2 => BOOL, 3 => INT'),
            'version' => $this->integer()->notNull()->defaultValue(1)->comment('配置的版本,每修改一次增加1'),
            'value' => $this->text()->comment('配置项value'),
            'last_publish_time' => $this->timestamp()->null()->comment('上次发布时间'),
            'last_publish_version' => $this->integer()->notNull()->defaultValue(0)->comment('上次发布的版本'),
            'state' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('配置项状态: 1 => 正常, 0 => 禁用, 1 => 删除'),
            'create_time' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('生成时间'),
            'update_time' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('更新时间'),
        ], "ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '配置表'");
        $this->createIndex('idx_key', 'configs', ['key'], false);
        $this->createIndex('idx_fullkey', 'configs', ['namespace_id', 'key'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('configs');
    }
}
