<?php

namespace lobtao\helper;

use lobtao\helper\commands\ServerReloadCommand;
use lobtao\helper\commands\ServerReStartCommand;
use lobtao\helper\commands\ServerStartCommand;
use lobtao\helper\commands\ServerStatusCommand;
use lobtao\helper\commands\ServerStopCommand;
use Swoole\Constant;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'commands' => [
                ServerStartCommand::class,
                ServerStopCommand::class,
                ServerStatusCommand::class,
                ServerReloadCommand::class,
                ServerReStartCommand::class,
            ],
            'server' => [
                'settings' => [
                    Constant::OPTION_DAEMONIZE => env('DAEMONIZE', false),
                    // Constant::OPTION_RELOAD_ASYNC => true, // 设置异步重启开关 swoole default
                    // Constant::OPTION_MAX_WAIT_TIME => 3, // 设置 Worker 进程收到停止服务通知后最大等待时间 swoole default
                ]
            ],
            'publish' => [
                [
                    'id' => 'server.sh',
                    'description' => 'The quick shell for server commands.',
                    'source' => __DIR__ . '/../publish/server.sh',
                    'destination' => BASE_PATH . '/server.sh',
                ],
                [
                    'id' => 'start.sh',
                    'description' => 'The quick shell for server commands.',
                    'source' => __DIR__ . '/../publish/start.sh',
                    'destination' => BASE_PATH . '/start.sh',
                ],
            ],
        ];
    }
}