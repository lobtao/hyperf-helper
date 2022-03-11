# hyperf-helper
#### 1. quick start -d/reload/restart/stop server
#### 2. support hyperf ~2.1.0 & ~2.2.0
#### 3. support CentOS7+, Ubuntu 18.0.4+, macOS
#### 4. support swoole-cli
### install
```bash
composer require lobtao/hyperf-helper:dev-main
```
### daemonize mode start server
```php
php ./bin/hyperf.php server:start -d
```
### stop server,default by master pid
```php
php ./bin/hyperf.php server:stop
```
### stop server by app name
```php
php ./bin/hyperf.php server:stop -a
```
### stop server by port
```php
php ./bin/hyperf.php server:stop -p
```
### safe reload worker&task process, default
```php
php ./bin/hyperf.php server:reload
```
### safe reload task process only
```php
php ./bin/hyperf.php server:reload -t
```
### view server status, default by master pid
```php
php ./bin/hyperf.php server:status
```
### view server status by app name
```php
php ./bin/hyperf.php server:status -a
```
### view server status by port
```php
php ./bin/hyperf.php server:status -p
```
### restart server
```php
php ./bin/hyperf.php server:restart
```
### quick shell
```shell
1. php ./bin/hyperf.php vendor:publish lobtao/hyperf-helper
2. chmod +x ./start.sh ./server.sh
3. command list
./start.sh
./server.sh start -d
./server.sh restart
./server.sh reload
./server.sh stop
./server.sh status
```