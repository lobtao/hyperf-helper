# hyperf-helper
server commands for hyperf ~2.1.0/~2.2.0

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
