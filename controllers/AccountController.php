<?php

namespace app\controllers;

use Yii;

class AccountController extends BaseController
{
    public function beforeAction($action)
    {
        Yii::$app->params['crumbs'] = [
            [
                'title' => '个人中心',
                'last' => false,
            ],
        ];
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        Yii::$app->params['crumbs'][] = [
            'title' => '个人信息',
            'last' => true,
        ];
        $user = Yii::$app->user->identity;
        return $this->render('index', ['user' => $user]);
    }

}
