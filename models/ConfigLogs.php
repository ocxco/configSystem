<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "config_logs".
 *
 * @property int $id 用户ID，自增
 * @property int $user_id 操作用户ID
 * @property int $config_id 配置ID
 * @property string $key 配置项key
 * @property int $type 配置类型: 0=> 字符串, 1 => 数组, 2 => BOOL, 3 => INT
 * @property int $version 配置的版本,每修改一次增加1
 * @property string $value 配置项value
 * @property string $create_time 生成时间
 */
class ConfigLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'config_id', 'type', 'version'], 'integer'],
            [['value'], 'string'],
            [['create_time'], 'safe'],
            [['key'], 'string', 'max' => 64],
            [['key'], 'unique'],
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
            'config_id' => 'Config ID',
            'key' => 'Key',
            'type' => 'Type',
            'version' => 'Version',
            'value' => 'Value',
            'create_time' => 'Create Time',
        ];
    }
}
