<?php

namespace app\controllers;

use app\helpers\AesHelper;
use Yii;
use app\helpers\MessageHelper;
use app\models\User;

class LoginController extends BaseController
{
    public $layout = false;

    public function actionLogin()
    {
        $loginData = $this->loadRemember();
        return $this->render('login', ['loginData' => $loginData]);
    }

    public function actionAjaxLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $remember = Yii::$app->request->post('remember');
        $user = User::findOne(['username' => $username]);
        if (empty($user)) {
            $this->failed('用户名或密码错误，请确认后重试');
        }
        if (!password_verify($password, $user->password)) {
            $this->failed('用户名或密码错误，请确认后重试');
        }
        if (Yii::$app->user->login($user)) {
            $this->remember($username, $password, $remember);
            $user->last_login_time = date('Y-m-d H:i:s');
            $user->save();
            MessageHelper::success('欢迎登陆《配置管理系统》，您上次登陆时间是:' . Yii::$app->user->identity->last_login_time);
            $this->success('登录成功');
        }
        $this->failed('登录失败');
    }

    private function remember($username, $password, $remember)
    {
        if ($remember) {
            // 记住密码
            $date = date('Y-m-d H:i:s');
            $content = "$username|$password|1|$date";
            $aesContent = AesHelper::encrypt($content);
            setcookie('token', $aesContent);
        } else {
            // 清空密码
            setcookie('token', '', strtotime('1970-01-01'));
        }
    }

    private function loadRemember()
    {
        $token = $_COOKIE['token'] ?? null;
        $res = [
            'username' => '',
            'password' => '',
            'remember' => 0,
        ];
        if ($token) {
            $data = AesHelper::decrypt($token);
            list($res['username'], $res['password'], $res['remember']) = explode('|', $data);
        }
        return $res;
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('/login/login');
    }

}
