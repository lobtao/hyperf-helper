# hyperf-helper
functions and server commands for hyperf

### 安装
config/autoload/server.php
```bash
composer require lobtao/hyperf-helper:dev-main
```
### 调试启动服务
```php
php ./bin/hyperf.php server:start
```
### 常驻启动服务
```php
php ./bin/hyperf.php server:start -d
```
### 停止服务,默认为根据pid结束进程
```php
php ./bin/hyperf.php server:stop
```
### 停止服务,根据app name结束进程
```php
php ./bin/hyperf.php server:stop -a
```
### 停止服务,根据port结束进程
```php
php ./bin/hyperf.php server:stop -p
```
### 查看服务状态
```php
php ./bin/hyperf.php server:status
```