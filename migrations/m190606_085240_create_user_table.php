<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190606_085240_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey()->comment('用户ID，自增'),
            'username' => $this->string(32)->notNull()->defaultValue('')->unique()->comment('用户名'),
            'password' => $this->string(128)->notNull()->defaultValue('')->comment('密码'),
            'mobile' => $this->char(11)->notNull()->defaultValue('')->unique()->comment('手机号'),
            'avatar' => $this->string(64)->notNull()->defaultValue('/images/avatars/profile-pic.jpg')->comment('用户头像'),
            'last_login_time' => $this->timestamp()->null()->comment('上次登录时间'),
            'last_reset_pass' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('上次修改密码时间'),
            'state' => $this->tinyInteger()->notNull()->defaultValue(1)->comment('用户状态: 0 => 禁用, 1 => 正常'),
            'create_time' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')->comment('生成时间'),
            'update_time' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->comment('更新时间'),
        ], "ENGINE=INNODB DEFAULT CHARSET=utf8 COMMENT '用户表'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
