<?php

namespace lobtao\helper;

use Swoole\Constant;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'commands' => [
                ServerStartCommand::class,
                ServerStopCommand::class,
                ServerStatusCommand::class
            ],
            'server' => [
                'settings' => [
                    Constant::OPTION_DAEMONIZE => env('DAEMONIZE', false),
                ]
            ]
        ];
    }
}