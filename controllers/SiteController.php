<?php

namespace app\controllers;

use Yii;

class SiteController extends BaseController
{

    /**
     * 错误处理.
     */
    public function actionError()
    {
        $exception = Yii::$app->getErrorHandler()->exception;
        if (empty($exception) || $exception->statusCode == 404) {
            if (Yii::$app->request->isAjax) {
                $this->failed('404', 'file not found');
            } else {
                return $this->render('404');
            }
        }
        if ($exception) {
            if (Yii::$app->request->isAjax) {
                $this->failed($exception->getMessage(), $exception->getTraceAsString());
            } else {
                return $this->render('500');
            }
        }
        return $this->render('500');
    }
}
