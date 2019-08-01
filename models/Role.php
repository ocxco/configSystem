<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $name roleName
 * @property string $create_time 创建时间
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_time'], 'safe'],
            [['name'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'create_time' => 'Create Time',
        ];
    }

    /**
     * 获取该角色下面的命名空间权限.
     * @return \yii\db\ActiveQuery
     *
     * @throws
     */
    public function getNamespaces()
    {
        return $this->hasMany(NSpace::class, ['id' => 'namespace_id'])
            ->viaTable(Role2namespace::tableName(), ['role_id' => 'id']);
    }

}
