<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\ProductPatentAgency;
use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Class UserController
 * @package app\commands
 */
class UserController extends Controller
{
    public function actionAdd($username, $password, $mobile = '')
    {
        if (empty($username)) {
            echo "请输入用户名" . PHP_EOL;
            return ExitCode::NOINPUT;
        }
        if (empty($password)) {
            echo "请输入密码" . PHP_EOL;
            return ExitCode::NOINPUT;
        }
        $user = User::findOne(['username' => $username]);
        if ($user) {
            echo "用户已存在" . PHP_EOL;
            return ExitCode::DATAERR;
        }
        $user = new User();
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_BCRYPT);
        if ($mobile) {
            $user->mobile = $mobile;
        }
        if ($user->save()) {
            echo "添加成功" . PHP_EOL;
            return ExitCode::OK;
        }
        echo "添加失败" . PHP_EOL;
        return ExitCode::OSERR;
    }

    public function actionTest($username, $password)
    {
        if (empty($username)) {
            echo "请输入用户名" . PHP_EOL;
            return ExitCode::NOINPUT;
        }
        if (empty($password)) {
            echo "请输入密码" . PHP_EOL;
            return ExitCode::NOINPUT;
        }
        $user = User::findOne(['username' => $username]);
        if (empty($user)) {
            echo "用户不存在" . PHP_EOL;
            return ExitCode::NOUSER;
        }
        if (!password_verify($password, $user->password)) {
            echo "密码错误" . PHP_EOL;
            return ExitCode::NOPERM;
        }
        echo "登录成功" . PHP_EOL;
        return ExitCode::OK;
    }
}
