<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta charset="utf-8"/>
    <title>PHP配置管理系统-登录</title>

    <meta name="description" content="User login page"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <?php include Yii::getAlias('@app/views/layouts/header.php'); ?>

</head>

<body class="login-layout light-login">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="ace-icon fa fa-leaf green"></i>
                            <span class="red">ConfigSystem</span>
                        </h1>
                        <h4 class="blue" id="id-company-text">&copy; CXC</h4>
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="ace-icon fa fa-coffee green"></i>
                                        请输入登录信息
                                    </h4>

                                    <div class="space-6"></div>

                                    <form id="loginForm">
                                        <fieldset>
                                            <div class="form-group">
                                                <label class="block clearfix">
                                                <span class="block input-icon input-icon-right">
                                                    <input type="text" name="username" class="form-control" placeholder="用户名"
                                                           aria-required="true" aria-invalid="true" value="<?= $loginData['username']; ?>"/>
                                                    <i class="ace-icon fa fa-user"></i>
                                                </span>
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label class="block clearfix">
                                                <span class="block input-icon input-icon-right">
                                                    <input type="password" name="password" class="form-control"
                                                           placeholder="密码" aria-required="true" aria-invalid="true" value="<?= $loginData['password']; ?>"/>
                                                    <i class="ace-icon fa fa-lock"></i>
                                                </span>
                                                </label>
                                            </div>
                                            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                                            <div class="space"></div>
                                            <div class="clearfix form-group">
                                                <label class="inline">
                                                    <input type="checkbox" class="ace" name="remember" aria-describedby="password-error" <?php if($loginData['remember']): ?>checked<?php endif;?>/>
                                                    <span class="lbl">记住密码</span>
                                                </label>

                                                <button type="submit"
                                                        class="width-35 pull-right btn btn-sm btn-primary login">
                                                    <i class="ace-icon fa fa-key"></i>
                                                    <span class="bigger-110">登录</span>
                                                </button>
                                            </div>

                                            <div class="space-4"></div>
                                        </fieldset>
                                    </form>
                                </div><!-- /.widget-main -->

                                <div class="toolbar clearfix">
                                    <div>
                                        <a href="#" data-target="#forgot-box" class="forgot-password-link">
                                            <i class="ace-icon fa fa-arrow-left"></i>
                                            忘记密码?
                                        </a>
                                    </div>

                                </div>
                            </div><!-- /.widget-body -->
                        </div><!-- /.login-box -->

                        <div id="forgot-box" class="forgot-box widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header red lighter bigger">
                                        <i class="ace-icon fa fa-key"></i>
                                        Retrieve Password
                                    </h4>

                                    <div class="space-6"></div>
                                    <p>
                                        Enter your email and to receive instructions
                                    </p>

                                    <form>
                                        <fieldset>
                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control"
                                                                   placeholder="Email"/>
															<i class="ace-icon fa fa-envelope"></i>
														</span>
                                            </label>

                                            <div class="clearfix">
                                                <button type="button" class="width-35 pull-right btn btn-sm btn-danger">
                                                    <i class="ace-icon fa fa-lightbulb-o"></i>
                                                    <span class="bigger-110">Send Me!</span>
                                                </button>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div><!-- /.widget-main -->

                                <div class="toolbar center">
                                    <a href="#" data-target="#login-box" class="back-to-login-link">
                                        Back to login
                                        <i class="ace-icon fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div><!-- /.widget-body -->
                        </div><!-- /.forgot-box -->

                    </div><!-- /.position-relative -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.main-content -->
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script src="/js/jquery-2.1.4.min.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="/js/jquery-1.11.3.min.js"></script>
<![endif]-->
<script src="/js/jquery.validate.min.js"></script>
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
</script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
    jQuery(function ($) {
        $('#loginForm').validate({
            errorElement: 'div',
            errorClass: 'help-block has-error',
            focusInvalid: false,
            ignore: "",
            rules: {
                username: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 5
                },
            },
            messages: {
                username: {
                    required: "请输入用户名",
                },
                password: {
                    required: "请输入密码",
                    minlength: "密码太短，请重新输入."
                },
            },
            submitHandler: function (form) {
                const data = $(form).serialize()
                $.post('/login/ajax-login', data, function (res) {
                    if (res.status == 0) {
                        alert(res.msg)
                    } else {
                        location.href = '/welcome/index';
                    }
                })
                return false;
            },
            invalidHandler: function (form) {
            }
        })
    });
</script>
</body>
</html>
