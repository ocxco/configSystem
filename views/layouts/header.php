<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta charset="utf-8" />
<title><?= $title ?? 'PHP配置管理系统'; ?></title>

<meta name="description" content="Static &amp; Dynamic Tables" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

<!-- bootstrap & fontawesome -->
<link rel="stylesheet" href="/css/bootstrap.min.css" />
<link rel="stylesheet" href="/font-awesome/4.5.0/css/font-awesome.min.css" />
<!-- text fonts -->
<link rel="stylesheet" href="/css/fonts.googleapis.com.css" />
<!-- ace styles -->
<link rel="stylesheet" href="/css/ace.min.css" />
<!--[if lte IE 9]>
<link rel="stylesheet" href="/css/ace-part2.min.css" />
<![endif]-->
<link rel="stylesheet" href="/css/ace-rtl.min.css" />
<!--[if lte IE 9]>
<link rel="stylesheet" href="/css/ace-ie.min.css" />
<![endif]-->
<link rel="stylesheet" href="/css/ace-skins.min.css" />

<?php foreach (Yii::$app->params['css'] ?? [] as $css): ?>
    <link rel="stylesheet" href="<?= $css; ?>?v=<?= Yii::$app->params['jsVersion']; ?>">
<?php endforeach; ?>
<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

<!-- ace settings handler -->
<script src="/js/ace-extra.min.js"></script>

<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

<!--[if lte IE 8]>
<script src="/js/html5shiv.min.js"></script>
<script src="/js/respond.min.js"></script>
<![endif]-->
