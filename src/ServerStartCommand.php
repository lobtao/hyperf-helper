<?php

declare(strict_types=1);

namespace lobtao\helper;

use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Swoole\Coroutine\System;
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
    }

    /**
     * watch pids status by htop
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        // $app_name = config('app_name');
        // passthru('ps -ef|grep '.$app_name.'|grep -v grep');
        $pids = getPids();
        $pids = implode(',', $pids);
        $ret = System::exec('which htop');
        if (empty($ret['output'])) {
            stdLog()->warning('htop not exists. You can `yum install htop` or `apt install htop` to install it');
        } else {
            passthru("htop -tp $pids");
        }
    }
}
