## 配置管理系统说明文档

### 简介
本系统基于[Yii2-Basic][]框架开发，前端页面采用[AdminLte][]模板

### 安装

    git clone https://github.com/ocxco/configSystem.git
    
    cd configSystem && composer install 

### 使用说明

* 配置修改

安装好composer依赖之后，复制config文件夹下面的几个相关example.php文件，去掉example

例如：`db.example.php` 复制为 `db.php`

修改db.php中的相关配置，连接上你的数据库.

修改web.php中configClient的相关配置，连接上你的etcd服务

* 数据初始化

运行migration初始化新建表以及初始化数据

    ./yii migrate/up

* 配置http服务

    按照yii2的文档进行配置即可

### Demo
[点击打开Demo][Demo]

Demo环境用户名/密码:
```
管理员: admin/admin 超管具有所有配置的管理权限
dba: dbuser/dbpass dba只有database命名空间的配置的管理权限
```
因为Demo环境没有配置etcd服务，因此无法发布配置

### 需要配合[配置管理客户端][configClient]使用

[Yii2-Basic]: https://www.yiichina.com/doc/guide/2.0  "Yii 2.0 权威指南"
[AdminLte]: https://adminlte.io/
[Demo]: http://config.591study.cn/
[configClient]: https://github.com/ocxco/configClient
