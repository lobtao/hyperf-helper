<?php

declare(strict_types=1);

namespace lobtao\helper;

use Hyperf\Command\Command as HyperfCommand;
use Symfony\Component\Console\Input\InputOption;

class ServerStatusCommand extends HyperfCommand
{

    public function __construct()
    {
        parent::__construct('server:status');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('server status');
        $this->addOption('DAEMONIZE', '-d', InputOption::VALUE_NONE, '是否优化');
    }

    /**
     */
    public function handle()
    {
        // $app_name = config('app_name');
        // passthru('ps -ef|grep '.$app_name.'|grep -v grep');
        $pids = getPids();
        $pids = implode(',', $pids);
        passthru("htop -p $pids");
    }
}
