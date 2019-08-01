<?php

namespace app\models;

use Yii;
use Yosymfony\Toml\Toml;

/**
 * This is the model class for table "configs".
 *
 * @property int $id 用户ID，自增
 * @property int $namespace_id 命名空间ID
 * @property string $key 配置项key
 * @property string $name 配置名称
 * @property int $type 配置类型: 0=> 字符串, 1 => 数组, 2 => BOOL, 3 => INT
 * @property int $version 配置的版本,每修改一次增加1
 * @property string $value 配置项value
 * @property string $last_publish_time 上次发布时间
 * @property int $last_publish_version 上次发布的版本
 * @property int $state 配置项状态: 1 => 正常, 0 => 禁用, 1 => 删除
 * @property string $create_time 生成时间
 * @property string $update_time 更新时间
 */
class Configs extends \yii\db\ActiveRecord
{
    const TYPE_STRING = 0;

    const TYPE_ARRAY = 1;

    const TYPE_BOOL = 2;

    const TYPE_NUMBER = 3;

    const TYPE_NULL = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configs';
    }

    public function init()
    {
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'saveLog']);
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'saveLog']);
    }

    public function saveLog()
    {
        $cl = new ConfigLogs();
        $cl->user_id = Yii::$app->user->id;
        $cl->config_id = $this->id;
        $cl->key = $this->key;
        $cl->type = $this->type;
        $cl->version = $this->version;
        $cl->value = $this->value;
        return $cl->save();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['namespace_id', 'type', 'version', 'last_publish_version', 'state'], 'integer'],
            [['value'], 'string'],
            [['last_publish_time', 'create_time', 'update_time'], 'safe'],
            [['key', 'name'], 'string', 'max' => 64],
            [['namespace_id', 'key'], 'unique', 'targetAttribute' => ['namespace_id', 'key']],
            ['value', 'validateValue', 'skipOnEmpty' => false],
            ['namespace_id', 'validateNs']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'namespace_id' => 'Namespace ID',
            'key' => 'Key',
            'name' => '配置说明',
            'type' => '配置类型',
            'version' => 'Version',
            'value' => '配置值',
            'last_publish_time' => 'Last Publish Time',
            'last_publish_version' => 'Last Publish Version',
            'state' => 'State',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'key',
            'name',
            'type',
            'value',
            'prefix',
            'namespace_id',
        ];
    }

    public function extraFields()
    {
        return [
            'ns',
            'realKey'
        ];
    }

    /**
     * 验证value是否合法
     * @return bool
     */
    public function validateValue()
    {
        switch ($this->type) {
            case self::TYPE_STRING:
                return true;
            case self::TYPE_ARRAY:
                try {
                    $r = Toml::parse($this->value);
                    if (!$r) {
                        $this->addError('value', '解析数组错误');
                        return false;
                    }
                    return true;
                } catch (\Exception $e) {

                    $this->addError('value', $e->getMessage());
                    return false;
                }
            case self::TYPE_BOOL:
                if ($this->value !== 'true' && $this->value !== 'false') {
                    $this->addError('value', '布尔值必须是true或false');
                    return false;
                }
                return true;
            case self::TYPE_NUMBER:
                if (!is_numeric($this->value)) {
                    $this->addError('value', '必须是数组');
                    return false;
                }
                return true;
            case self::TYPE_NULL:
                $this->value = 'NULL';
                return true;
        }
    }

    /**
     * 验证namespace是否有权限.
     * @return bool
     */
    public function validateNs()
    {
        $nss = Yii::$app->user->identity->nss;
        foreach ($nss as $ns) {
            if ($ns->id == $this->namespace_id || $ns->namespace == '/') {
                return true;
            }
        }
        $this->addError('namespace_id', "你无权限编辑或发布该命名空间下的配置");
        return false;
    }

    /**
     * @param array $params
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public static function getList($params, $page = 1, $pageSize = 20)
    {
        $offset = ($page - 1) * $pageSize;
        $query = self::find();
        $cond = [
            'state' => 1,
        ];
        if (!empty($params['nsId'])) {
            $cond['namespace_id'] = $params['nsId'];
        }
        if (!empty($params['ids'])) {
            $cond['id'] = $params['ids'];
        }
        $query->where($cond);
        if (!empty($params['keyword'])) {
            $query->andWhere(['LIKE', 'key', $params['keyword']]);
        }
        $count = $query->count();
        $list = $query->offset($offset)->orderBy('id DESC')->limit($pageSize)->all();
        return [
            'count' => $count,
            'list' => $list,
        ];
    }

    public function getNs()
    {
        return $this->hasOne(NSpace::class, ['id' => 'namespace_id']);
    }

    public function getPrefix()
    {
        return $this->ns->prefix;
    }

    public function getRealKey()
    {
        return $this->ns->prefix . ltrim($this->key, '/');
    }

    public function getTypeName()
    {
        switch ($this->type) {
            case self::TYPE_STRING:
                return '字符串';
            case self::TYPE_ARRAY:
                return '数组';
            case self::TYPE_BOOL:
                return '布尔';
            case self::TYPE_NUMBER:
                return '数字';
            case self::TYPE_NULL:
                return 'NULL';
        }
    }

    public function publish()
    {
        $this->last_publish_time = date('Y-m-d H:i:s');
        $this->last_publish_version = $this->version;
        if ($this->save()) {
            try {
                Yii::$app->configClient->publish($this);
                return true;
            } catch (\Exception $e) {
                $this->addError('id', $e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

}
