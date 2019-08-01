<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role2namespace".
 *
 * @property int $id
 * @property int $role_id roleId
 * @property int $namespace_id namespaceId
 * @property string $create_time 创建时间
 */
class Role2namespace extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role2namespace';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'namespace_id'], 'integer'],
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
            'role_id' => 'Role ID',
            'namespace_id' => 'Namespace ID',
            'create_time' => 'Create Time',
        ];
    }
}
