<?php

namespace app\controllers;

use Yii;
use app\models\Configs;

class WelcomeController extends BaseController
{
    public function beforeAction($action)
    {
        Yii::$app->params['crumbs'] = [
            [
                'title' => '控制台',
                'last' => false,
            ],
        ];
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        Yii::$app->params['crumbs'][] = [
            'title' => '概览',
            'last' => true,
        ];
        return $this->render('index');
    }

}
