# hyperf-helper
functions and server commands for hyperf ~2.1.0/~2.2.0

### install
```bash
composer require lobtao/hyperf-helper:dev-main
```
### debug mode start server
```php
php ./bin/hyperf.php server:start
```
### daemonize mode start server
```php
php ./bin/hyperf.php server:start -d
```
### stop server,default by pid
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
### reload worker&task process
```php
php ./bin/hyperf.php server:reload
```
### reload worker process
```php
php ./bin/hyperf.php server:reload -w
```
### reload task process
```php
php ./bin/hyperf.php server:reload -t
```
### view server status, default by pid
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
