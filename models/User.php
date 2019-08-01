<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id 用户ID，自增
 * @property string $username 用户名
 * @property string $password 密码
 * @property string $mobile 手机号
 * @property string $last_login_time 上次登录时间
 * @property string $last_reset_pass 上次修改密码时间
 * @property int $state 用户状态: 0 => 禁用, 1 => 正常
 * @property string $create_time 生成时间
 * @property string $update_time 更新时间
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    private $_namespaces = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['last_login_time', 'last_reset_pass', 'create_time', 'update_time'], 'safe'],
            [['state'], 'integer'],
            [['username'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 128],
            [['mobile'], 'string', 'max' => 11],
            [['username'], 'unique'],
            [['mobile'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'mobile' => 'Mobile',
            'last_login_time' => 'Last Login Time',
            'last_reset_pass' => 'Last Reset Pass',
            'state' => 'State',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getShowMobile()
    {
        if (empty($this->mobile)) {
            return '';
        }
        return substr($this->mobile, 0, 3) . '****' . substr($this->mobile, -4);
    }

    public function getLastOnline()
    {
        if (empty($this->last_login_time)) {
            return '刚刚';
        }
        $diff = date_diff(new \DateTime($this->last_login_time), new \DateTime());
        if ($diff->y) {
            return "{$diff->y} 年前";
        } elseif ($diff->days) {
            return "{$diff->days} 天前";
        } elseif ($diff->h) {
            return "{$diff->h} 小时前";
        } elseif ($diff->i) {
            return "{$diff->i} 分钟前";
        } else {
            return "{$diff->s} 秒前";
        }
    }

    /**
     * 获取用户角色
     * @return \yii\db\ActiveQuery
     *
     * @throws
     */
    public function getRoles()
    {
        return $this->hasMany(Role::class, ['id' => 'role_id'])->viaTable(User2role::tableName(), ['user_id' => 'id']);
    }

    /**
     * 获取用户的命名空间权限.
     * @return NSpace[]
     */
    public function getNss()
    {
        if (empty($this->_namespaces)) {
            $ns = [];
            foreach ($this->roles as $role) {
                if ($role->name == 'admin') {
                    $ns = NSpace::find()->all();
                    break;
                } else {
                    $ns = array_merge($role->namespaces, $ns);
                }
            }
            $this->_namespaces = $ns;
        }
        return $this->_namespaces;
    }

}
