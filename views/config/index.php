<div class="row">
    <style>
        th,td {
            text-align: center;
        }
    </style>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-xs-12">
                <div class="search-form">
                    <form action="" class="form-inline form-search">
                        <div class="form-group btnAdd">
                            <button type="button" class="btn btn-primary btn-sm" id="addConfig">
                                <span class="ace-icon fa fa-plus-circle icon-on-right bigger-110"></span>
                                新增
                            </button>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="nsId">
                                <option value="">- 命名空间 -</option>
                                <?php foreach (Yii::$app->user->identity->nss as $n): ?>
                                    <option value="<?= $n->namespace == '/' ? 0 : $n->id ?>" <?php if ($params['nsId'] == $n->id): ?>selected<?php endif; ?>><?= $n->namespace ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" name="state">
                                <option value="">-配置状态-</option>
                                <option value="1" <?php if ($params['state'] === '1'): ?>selected<?php endif; ?>>正常</option>
                                <option value="0" <?php if ($params['state'] === '0'): ?>selected<?php endif; ?>>已禁用</option>
                                <option value="-1" <?php if ($params['state'] === '-1'): ?>selected<?php endif; ?>>已删除</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control search-query" name="keyword" placeholder="搜索key" value="<?= $params['keyword']?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-purple btn-sm">
                                <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                                搜索
                            </button>
                        </div>
                        <div class="form-group pull-right">
                            <a class="btn btn-sm btn-default btnPublish publishSelected"
                               href="javascript:;"
                               data-<?= Yii::$app->request->csrfParam?>="<?= Yii::$app->request->csrfToken ?>"
                            >
                                发布选中配置
                            </a>
                            <a class="btn btn-sm btn-danger publishAll"
                               href="javascript:;"
                               data-<?= Yii::$app->request->csrfParam?>="<?= Yii::$app->request->csrfToken ?>"
                            >
                                发布本页所有配置
                            </a>
                        </div>
                    </form>
                </div>
                <div>
                    <table id="configList" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>配置项</th>
                            <th>配置名称</th>
                            <th>类型</th>
                            <th style="width: 500px">当前值</th>
                            <th>当前版本</th>
                            <th>已发布版本</th>
                            <th>上次发布时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($list as $item): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="choosePublish" data-id="<?= $item->id ?>">
                                <?= $item->id ?>
                            </td>
                            <td><?= $item->realkey ?></td>
                            <td><?= $item->name ?></td>
                            <td><?= $item->typeName ?></td>
                            <?php if ($item->type == \app\models\Configs::TYPE_ARRAY): ?>
                                <td class="valuePreview shortShow"
                                    data-<?= Yii::$app->request->csrfParam?>="<?= Yii::$app->request->csrfToken ?>"
                                    data-id="<?= $item->id ?>"
                                >
                                        <?= str_replace(["\n", "\r\n"], "<br />", $item->value) ?>
                                </td>
                            <?php else: ?>
                                <td>
                                    <?= str_replace(["\n", "\r\n"], "<br />", $item->value) ?>
                                </td>
                            <?php endif; ?>
                            <td><?= $item->version ?></td>
                            <td><?= $item->last_publish_version ?></td>
                            <td><?= $item->last_publish_time ?></td>
                            <td>
                                <button class="btn btn-sm btn-primary configEdit" data-id='<?= $item->id ?>' type="button">
                                    编辑
                                </button>
                                <button class="btn btn-sm btn-primary configPublish"
                                        type="button"
                                        data-id="<?= $item->id ?>"
                                        data-<?= Yii::$app->request->csrfParam?>="<?= Yii::$app->request->csrfToken ?>"
                                >
                                    发布
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?= $linkPager ?>
                </div>
            </div>
        </div>
        <div id="configModal" class="modal fade in" tabindex="-1" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="smaller lighter blue no-margin">新增/编辑配置</h3>
                    </div>
                    <div class="modal-body">
                        <form id="configForm" class="form form-horizontal">
                            <input type="hidden" value="" name="id" id="configId">
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="nsId">命名空间</label>
                                <select class="col-sm-6" name="namespace_id" id="nsId">
                                    <?php foreach (Yii::$app->user->identity->nss as $n): ?>
                                        <option value="<?=$n->id ?>" data-prefix="<?= $n->prefix?>"><?= $n->prefix ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="configKey">配置Key</label>
                                <div class="input-group col-sm-6">
                                    <span class="input-group-addon prefix" data-prefix="<?= Yii::$app->user->identity->nss[0]->prefix?>"><?= Yii::$app->user->identity->nss[0]->prefix?></span>
                                    <input class="form-control" type="text" name="key" id="configKey">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="configName">配置名称</label>
                                <input class="col-sm-6" type="text" name="name" id="configName">
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="configType">配置类型</label>
                                <select class="col-sm-6" name="type" id="configType">
                                    <option value="0">字符串</option>
                                    <option value="1">数组</option>
                                    <option value="2">布尔</option>
                                    <option value="3">数字</option>
                                    <option value="4">NULL</option>
                                </select>
                                <div class="col-sm-1" id="configPreviewDiv">
                                    <a class="btn btn-sm" id="configPreview">
                                        预览
                                    </a>
                                </div>
                            </div>
                            <div class="form-group configValue">
                                <label class="control-label col-sm-2" for="configValue">配置值</label>
                                <textarea class="col-sm-9" rows="8" name="value" id="configValue"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-sm btn-warning pull-left" type="button" data-dismiss="modal">
                                    取消
                                </button>
                                <button class="btn btn-sm btn-primary pull-right" type="submit">
                                    确定
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="previewModal" class="modal fade in" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" style="height: 100%">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="smaller lighter blue no-margin">配置预览</h3>
                    </div>
                    <div class="modal-body">
                        <pre id="previewValue">
                            array()
                        </pre>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
