<?php
/**
 * Created by PhpStorm.
 * User: xuechaoc
 * Date: 2019-06-10
 * Time: 17:10
 */

namespace app\components;

use app\models\Configs;
use EtcdPHP\Client;
use EtcdPHP\ClientInterface;
use yii\base\Component;

/**
 * 配置管理接口.
 *
 * @property string $key
 * @property int $type
 * @property string $value
 */
class ConfigClient extends Component
{

    public $server;

    public $user;

    public $password;

    /**
     * @var ClientInterface
     */
    private $_client;

    public function init()
    {
        parent::init();
        $this->_client = Client::instance($this->server);
        if ($this->user && $this->password) {
            $this->_client->auth($this->user, $this->password);
        }
    }

    /**
     * 发布一个配置.
     * @param Configs $config
     * @return \EtcdPHP\Response
     * @throws \Exception
     */
    public function publish($config)
    {
        $data = json_encode(['type' => $config->type, 'value' => $config->value]);
        return $this->_client->set($config->realKey, $data);
    }

    /**
     * 删除一个key.
     * @param Configs $config
     * @return \EtcdPHP\Response
     */
    public function delete($config)
    {
        return $this->_client->rm($config->key);
    }

}

