<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user2role".
 *
 * @property int $id
 * @property int $user_id userId
 * @property int $role_id roleId
 * @property string $create_time 创建时间
 */
class User2role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user2role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id'], 'integer'],
            [['create_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'role_id' => 'Role ID',
            'create_time' => 'Create Time',
        ];
    }

}
