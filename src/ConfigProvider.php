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
        $config = [
            'commands' => [
                ServerStartCommand::class,
                ServerStopCommand::class,
                ServerStatusCommand::class,
                ServerReloadCommand::class,
                ServerReStartCommand::class,
            ],
            'server'   => [
                'settings' => [
                ],
            ],
            'publish'  => [
                [
                    'id'          => 'server.sh',
                    'description' => 'The quick shell for server commands.',
                    'source'      => __DIR__ . '/../publish/server.sh',
                    'destination' => BASE_PATH . '/server.sh',
                ],
                [
                    'id'          => 'start.sh',
                    'description' => 'The quick shell for server commands.',
                    'source'      => __DIR__ . '/../publish/start.sh',
                    'destination' => BASE_PATH . '/start.sh',
                ],
            ],
        ];

        $option_daemonize = env('DAEMONIZE', false);
        if ($option_daemonize) {
            $log_file = BASE_PATH . '/runtime/logs/';
            if (!file_exists($log_file)) {
                mkdir($log_file);
            }
            $log_file .= 'hyperf.out.log';
            $config['server']['settings'][Constant::OPTION_DAEMONIZE] = true;
            $config['server']['settings'][Constant::OPTION_LOG_FILE] = $log_file;
            // $config['server']['settings'][Constant::OPTION_RELOAD_ASYNC] = true; // 设置异步重启开关 swoole default
            // $config['server']['settings'][Constant::OPTION_MAX_WAIT_TIME] = 3; // 设置 Worker 进程收到停止服务通知后最大等待时间 swoole default
        }
        return $config;
    }
}