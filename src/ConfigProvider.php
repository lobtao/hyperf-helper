<?php

namespace lobtao\helper;

use lobtao\helper\commands\ServerReloadCommand;
use lobtao\helper\commands\ServerReStartCommand;
use lobtao\helper\commands\ServerStartCommand;
use lobtao\helper\commands\ServerStatusCommand;
use lobtao\helper\commands\ServerStopCommand;
use lobtao\helper\listeners\ConsoleCommandEventListener;

class ConfigProvider
{
    public function __invoke(): array
    {
        $config = [
            'commands' => [
                ServerStopCommand::class,
                ServerReloadCommand::class,
                ServerStatusCommand::class,
                ServerStartCommand::class,
                ServerReStartCommand::class,
            ],
            'publish' => [
                [
                    'id' => 'server.sh',
                    'description' => 'The quick control for server commands.',
                    'source' => __DIR__ . '/../publish/server.sh',
                    'destination' => BASE_PATH . '/server.sh',
                ],
                [
                    'id' => 'composer.sh',
                    'description' => 'The quick composer for update project.',
                    'source' => __DIR__ . '/../publish/composer.sh',
                    'destination' => BASE_PATH . '/composer.sh',
                ]
            ],
        ];

        return $config;
    }
}
