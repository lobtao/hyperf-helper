<?php

declare(strict_types=1);

namespace lobtao\helper\commands;

use Hyperf\Command\Command as HyperfCommand;
use Symfony\Component\Console\Input\InputOption;

class ServerReStartCommand extends HyperfCommand
{

    public function __construct()
    {
        parent::__construct('server:restart');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('server restart');
        $this->addOption('default', '-d', InputOption::VALUE_NONE, 'restart server');
    }

    /**
     */
    public function handle()
    {
        $this->call('server:stop');
        $this->call('server:start', ['-d' => true]);
    }
}