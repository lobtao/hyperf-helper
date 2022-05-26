# hyperf-helper
#### 1. quick start -d/reload/restart/stop server
#### 2. support hyperf ~2.1.0 & ~2.2.0
#### 3. support CentOS7+, Ubuntu 18.0.4+, macOS
#### 4. support swoole-cli
#### 5. support hyperf phar
### install
```bash
composer require lobtao/hyperf-helper:dev-main
```
### daemonize mode start server
```php
php ./bin/hyperf.php server:start -d
swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php server:start -d
php ./hyperf-skeleton.phar server:start -d
```
### stop server,default by master pid
```php
php ./bin/hyperf.php server:stop
swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php server:stop
php ./hyperf-skeleton.phar server:stop
```
### stop server by app name
```php
php ./bin/hyperf.php server:stop -a
swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php server:stop -a
php ./hyperf-skeleton.phar server:stop -a
```
### stop server by port
```php
php ./bin/hyperf.php server:stop -p
swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php server:stop -p
php ./hyperf-skeleton.phar server:stop -p
```
### safe reload worker&task process, default
```php
php ./bin/hyperf.php server:reload
swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php server:reload
php ./hyperf-skeleton.phar server:reload
```
### safe reload task process only
```php
php ./bin/hyperf.php server:reload -t
swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php server:reload -t
php ./hyperf-skeleton.phar server:reload -t
```
### view server status, default by master pid
```php
php ./bin/hyperf.php server:status
swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php server:status
php ./hyperf-skeleton.phar server:status
```
### view server status by app name
```php
php ./bin/hyperf.php server:status -a
swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php server:status -a
php ./hyperf-skeleton.phar server:status -a
```
### view server status by port
```php
php ./bin/hyperf.php server:status -p
swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php server:status -p
php ./hyperf-skeleton.phar server:status -p
```
### restart server
```php
php ./bin/hyperf.php server:restart
swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php server:restart
php ./hyperf-skeleton.phar server:restart
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
