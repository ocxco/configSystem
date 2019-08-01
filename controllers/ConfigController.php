<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\widgets\LinkPager;
use Yosymfony\Toml\Toml;
use app\components\ConfigClient;
use app\models\Configs;
use app\models\NSpace;

class ConfigController extends BaseController
{

    private static $defaultParams = [
        'nsId' => 0,
        'keyword' => '',
        'state' => 1,
        'ids' => [],
    ];

    /**
     * 获取单个配置.
     * @param $id
     * @return Configs|null
     */
    private function getConfig($id, $validate = true)
    {
        $nss = [];
        foreach (Yii::$app->user->identity->nss as $ns) {
            $nss[] = $ns->id;
        }
        $config = Configs::findOne(['id' => $id]);
        if ($validate && empty($config)) {
            $this->failed('对不起，配置不存在');
        }
        if ($validate && !in_array($config->namespace_id, $nss)) {
            $this->failed('对不起，你无权查看该配置');
        }
        return $config;
    }

    public function beforeAction($action)
    {
        Yii::$app->params['crumbs'] = [
            [
                'title' => '配置管理',
                'last' => false,
            ],
        ];
        return parent::beforeAction($action);
    }

    /**
     * 配置管理列表.
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        Yii::$app->params['crumbs'][] = [
            'title' => '申请列表',
            'last' => true,
        ];
        $params = array_merge(self::$defaultParams, Yii::$app->request->get());
        $page = $params['page'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;
        if (empty($params['nsId'])) {
            $params['nsId'] = [];
            foreach (Yii::$app->user->identity->nss as $ns) {
                $params['nsId'][] = $ns->id;
            }
        }
        $list = Configs::getList($params, $page, $pageSize);
        $pagination = new Pagination([
            'totalCount' => $list['count'],
            'pageSize' => $pageSize,
            'page' => $page - 1,
            'params' => $params,
            'pageParam' => 'page',
            'pageSizeParam' => 'pageSize'
        ]);
        $data = [
            'title' => '配置列表',
            'list' => $list['list'],
            'params' => $params,
            'linkPager' => LinkPager::widget([
                'pagination' => $pagination,
                'disableCurrentPageButton' => true,
                'hideOnSinglePage' => false,
            ]),
        ];
        $this->registerJs('/js/jquery.validate.min.js');
        $this->registerJs('/js/bootbox.js');
        $this->registerJs('/js/bootstrap-datepicker.min.js');
        $this->registerJs('/js/custom/config.js');
        $this->registerJs('/js/layer/layer.js');
        return $this->render('index', $data);
    }

    /**
     * 编辑/新增 配置.
     */
    public function actionEdit()
    {
        $data = Yii::$app->request->post();
        $ns = NSpace::findOne(['id' => $data['namespace_id']]);
        if (empty($ns)) {
            $this->failed('命名空间错误');
        }
        if ($data['id']) {
            $msg = '编辑';
            $config = Configs::findOne(['id' => $data['id']]);
            if ($config->value == $data['value']
                && $config->key == $data['key']
                && $config->name == $data['name']
                && $config->type == $data['type']
            ) {
                $this->failed('没有变更');
            }
            unset($data['id']);
            $config->setAttributes($data);
            $config->version = $config->version + 1;
        } else {
            $msg = '新增';
            $config = new Configs();
            $config->setAttributes($data);
            $config->version = 1;
        }
        if ($config->save()) {
            $this->success($msg . '成功');
        }
        $this->failed($msg . '失败。' . var_export($config->firstErrors, true));
    }

    /**
     * 解析Toml语法以php数组方式显示.
     */
    public function actionPreview()
    {
        $configId = Yii::$app->request->post('id');
        $config = $this->getConfig($configId, $configId);
        if (!$config) {
            $config = new Configs();
            $config->type = Configs::TYPE_ARRAY;
            $config->value = Yii::$app->request->post('value');
        }
        switch ($config->type) {
            case Configs::TYPE_ARRAY:
                try {
                    $value = Toml::parse($config->value);
                    if (!$value) {
                        $this->failed('解析失败，请修改');
                    }
                    $this->success('success', var_export($value, true));
                } catch (\Exception $e) {
                    $this->failed($e->getMessage());
                }
                break;
            case Configs::TYPE_NULL:
                $this->success('success', var_export(null, true));
                break;
            default:
                $this->success('success', var_export($config->value, true));
                break;
        }
    }

    /**
     * 获取配置.
     */
    public function actionGet()
    {
        $configId = Yii::$app->request->get('id');
        $config = $this->getConfig($configId);
        $this->success('success', $config);
    }

    /**
     * 指定发布某个配置.
     */
    public function actionPublish()
    {
        $id = Yii::$app->request->post('id');
        $config = Configs::findOne(['id' => $id]);
        if (!$config) {
            $this->failed('配置不存在');
        }
        $config->last_publish_time = date('Y-m-d H:i:s');
        $config->last_publish_version = $config->version;
        if ($config->publish()) {
            $this->success('发布成功');
        } else {
            $this->failed('发布失败。' . var_export($config->firstErrors, true));
        }
    }

    /**
     * 发布全部(有权限的配置)
     */
    public function actionPublishAll()
    {
        $ids = Yii::$app->request->post('ids');
        $params = self::$defaultParams;
        if (empty($params['nsId'])) {
            $params['nsId'] = [];
            foreach (Yii::$app->user->identity->nss as $ns) {
                $params['nsId'][] = $ns->id;
            }
        }
        if (empty($ids)) {
            $this->failed('至少选中一个配置.');
        }
        $params['ids'] = $ids;
        $failed = [];
        $page = 1;
        while ($list = Configs::getList($params, $page, 100)) {
            if (empty($list['list'])) {
                break;
            }
            foreach ($list['list'] as $item) {
                try {
                    if (!$item->publish()) {
                        $failed[$item->realkey] = $item->firstErrors;
                    }
                } catch (\Exception $e) {
                    $failed[$item->realkey] = $e->getMessage();
                }
            }
            $page++;
        }
        if (empty($failed)) {
            $this->success('批量发布配置成功');
        }
        $this->failed('以下配置发布失败', var_export($failed));
    }


}
