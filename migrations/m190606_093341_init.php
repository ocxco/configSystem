<?php

use yii\db\Migration;

/**
 * Class m190606_093341_init
 */
class m190606_093341_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /***  添加用户 ***/
        $adm = new \app\models\User();
        $adm->username = 'admin';
        $adm->password = password_hash('admin', PASSWORD_BCRYPT);
        $adm->mobile = '15555555555';
        $adm->save();

        $dbuser = new \app\models\User();
        $dbuser->username = 'dbuser';
        $dbuser->password = password_hash('dbpass', PASSWORD_BCRYPT);
        $dbuser->mobile = '15555555555';
        $dbuser->save();

        /*** 添加角色 ***/
        $admin = new \app\models\Role();
        $admin->name = 'admin';
        $admin->save();
        $dba = new \app\models\Role();
        $dba->name = 'dba';
        $dba->save();
        /**** 添加角色End ****/

        /*** 添加命名空间 ***/
        // 根目录
        $root = new \app\models\NSpace();
        $root->namespace = '/';
        $root->save();
        // 数据库相关
        $db = new \app\models\NSpace();
        $db->namespace = '/database';
        $db->save();
        /*** 添加命名空间End ***/

        /*** 添加用户->角色映射 ***/
        $u2r = new \app\models\User2role();
        $u2r->user_id = $adm->id;
        $u2r->role_id = $admin->id;
        $u2r->save();
        $u2r = new \app\models\User2role();
        $u2r->user_id = $dbuser->id;
        $u2r->role_id = $dba->id;
        $u2r->save();
        /*** 添加用户->角色映射End ***/

        /*** 添加角色->命名空间映射 ***/
        $r2n = new \app\models\Role2namespace();
        $r2n->role_id = $admin->id;
        $r2n->namespace_id = $root->id;
        $r2n->save();
        $r2n = new \app\models\Role2namespace();
        $r2n->role_id = $dba->id;
        $r2n->namespace_id = $db->id;
        $r2n->save();
        /*** 添加角色->命名空间映射End ***/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('user');
        $this->truncateTable('role');
        $this->truncateTable('namespace');
        $this->truncateTable('user2role');
        $this->truncateTable('role2namespace');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190606_093341_init cannot be reverted.\n";

        return false;
    }
    */
}
