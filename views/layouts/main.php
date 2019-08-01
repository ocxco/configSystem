<!DOCTYPE html>
<html lang="en">
<head>
    <?php include Yii::getAlias('@app/views/layouts/header.php'); ?>
</head>

<body class="no-skin">
<?php include Yii::getAlias('@app/views/layouts/navbar.php'); ?>
<div class="main-container ace-save-state" id="main-container">
    <script type="text/javascript">
        try {
            ace.settings.loadState('main-container')
        } catch (e) {
        }
    </script>

    <?php include Yii::getAlias('@app/views/layouts/sidebar.php'); ?>
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="/welcome/index">Home</a>
                    </li>
                    <?php foreach (Yii::$app->params['crumbs'] ?? [] as $crumb): ?>
                        <li class="<?php if ($crumb['last']): ?>active<?php endif; ?>">
                            <?php if (!empty($crumb['url'])): ?>
                                <a href="<?= $crumb['url'] ?>"><?= $crumb['title'] ?></a>
                            <?php else: ?>
                                <?= $crumb['title'] ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul><!-- /.breadcrumb -->
            </div>

            <div class="page-content">
                <?php include Yii::getAlias('@app/views/layouts/settings.php'); ?>
                <div class="page-header">
                    <?php foreach (Yii::$app->params['crumbs'] ?? [] as $k => $crumb): ?>
                        <h<?= min(3, ($k+1))?> class="crumb-title">
                            <?php if ($crumb['last']): ?>
                            <small><?= $crumb['title'] ?></small>
                            <?php else: ?>
                                <?= $crumb['title'] ?>
                                <i class="ace-icon fa fa-angle-double-right"></i>
                            <?php endif; ?>
                        </h<?= min(3, ($k+1))?>>
                    <?php endforeach; ?>
                </div><!-- /.page-header -->

                <div class="row">
                    <?php foreach (\app\helpers\MessageHelper::$msg as $msg): ?>
                        <div class="alert alert-block alert-<?= $msg['type']; ?>">
                            <button type="button" class="close" data-dismiss="alert">
                                <i class="ace-icon fa fa-times"></i>
                            </button>
                            <?= $msg['msg']; ?>
                        </div>
                    <?php endforeach; ?>
                    <?= $content; ?>
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

    <div class="footer">
        <div class="footer-inner" style="left: 0;">
            <div class="footer-content" style="bottom: initial">
                <span class="bigger-120">
                    CXC &copy; 2015-2020
                </span>
            </div>
        </div>
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script src="/js/jquery-2.1.4.min.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="/js/jquery-1.11.3.min.js"></script>
<![endif]-->
<script type="text/javascript">
    if ('ontouchstart' in document.documentElement) document.write("<script src='/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
</script>
<script src="/js/bootstrap.min.js"></script>

<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
<script src="/js/excanvas.min.js"></script>
<![endif]-->
<script src="/js/jquery-ui.custom.min.js"></script>
<script src="/js/jquery.ui.touch-punch.min.js"></script>
<script src="/js/jquery.easypiechart.min.js"></script>
<script src="/js/jquery.sparkline.index.min.js"></script>
<script src="/js/jquery.flot.min.js"></script>
<script src="/js/jquery.flot.pie.min.js"></script>
<script src="/js/jquery.flot.resize.min.js"></script>

<!-- ace scripts -->
<script src="/js/ace-elements.min.js"></script>
<script src="/js/ace.min.js"></script>
<?php foreach (Yii::$app->params['js'] ?? [] as $js): ?>
    <script src="<?= $js; ?>?v=<?= Yii::$app->params['jsVersion']; ?>"></script>
<?php endforeach; ?>
</body>
</html>
