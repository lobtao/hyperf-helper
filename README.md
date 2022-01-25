# hyperf-helper
functions and server commands for hyperf

### 配置
config/autoload/server.php
```php
return [
    ...
    'settings' => [
        ...
        Constant::OPTION_DAEMONIZE => env('DAEMONIZE',false), // 后台常驻运行
    ]
];
```
config/autoload/commands.php
```php
return [
    ...
    \lobtao\helper\ServerStartCommand::class,
    \lobtao\helper\ServerStopCommand::class,
    \lobtao\helper\ServerStatusCommand::class,
];
```
### 调试启动服务
```php
php ./bin/hyperf.php server:start
```
### 常驻启动服务
```php
php ./bin/hyperf.php server:start -d
```
### 停止服务
```php
php ./bin/hyperf.php server:stop
```
### 查看服务状态
```php
php ./bin/hyperf.php server:status
```