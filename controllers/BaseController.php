<?php

namespace app\controllers;

use Yii;
use yii\base\InlineAction;
use yii\web\Controller;
use yii\web\Response;

class BaseController extends Controller
{

    const STATUS_FAILED = 0;

    const STATUS_SUCCESS = 1;

    const STATUS_NEED_LOGIN = -1;

    /**
     * @param InlineAction $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest && !in_array($action->controller->id, ['login', 'site'])) {
            $this->redirect('/login/login');
            return false;
        }
        $this->registerCss('/css/custom/common.css');
        return parent::beforeAction($action);
    }

    /**
     * @param string $msg 返回的消息.
     * @param array $data 返回的数据(如果有的话).
     * @param int $status 响应Code(0:成功,1:失败).
     *
     * @throws
     */
    public function failed($msg, $data = null, $status = self::STATUS_FAILED)
    {
        $return = [
            'status'  => $status,
            'msg'     => $msg,
            'data'    => $data,
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data = $return;
        Yii::$app->end();
    }

    public function success($msg, $data = null)
    {
        $this->failed($msg, $data, self::STATUS_SUCCESS);
    }

    public function registerJs($js)
    {
        Yii::$app->params['js'][] = $js;
    }

    public function registerCss($css)
    {
        Yii::$app->params['css'][] = $css;
    }

}
