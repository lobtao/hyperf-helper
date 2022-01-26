<?php

namespace lobtao\helper;

use lobtao\helper\commands\ServerReloadCommand;
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
                ServerReloadCommand::class
            ],
            'server' => [
                'settings' => [
                    Constant::OPTION_DAEMONIZE => env('DAEMONIZE', false),
                ]
            ]
        ];
    }
}