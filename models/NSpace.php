<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "namespace".
 *
 * @property int $id
 * @property string $namespace 命名空间
 * @property string $create_time 创建时间
 */
class NSpace extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'namespace';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_time'], 'safe'],
            [['namespace'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'namespace' => 'Namespace',
            'create_time' => 'Create Time',
        ];
    }

    public function getPrefix()
    {
        return rtrim($this->namespace ,'/') . '/';
    }

}
