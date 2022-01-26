# hyperf-helper
functions and server commands for hyperf

### 安装
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
### 重启worker&task进程
```php
php ./bin/hyperf.php server:reload
```
### 重启worker进程
```php
php ./bin/hyperf.php server:reload -w
```
### 重启task进程
```php
php ./bin/hyperf.php server:reload -t
```
### 查看服务状态,默认为根据pid查看进程信息
```php
php ./bin/hyperf.php server:status
```
### 查看服务状态,根据app name查看进程信息
```php
php ./bin/hyperf.php server:status -a
```
### 查看服务状态,根据port查看进程信息
```php
php ./bin/hyperf.php server:status -p
```
