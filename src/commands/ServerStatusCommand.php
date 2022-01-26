<?php

declare(strict_types=1);

namespace lobtao\helper\commands;

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
        $this->addOption('appname', '-a', InputOption::VALUE_NONE, 'process status by app name');
        $this->addOption('port', '-p', InputOption::VALUE_NONE, 'process status by port');

    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        // $app_name = config('app_name');
        // passthru('ps -ef|grep '.$app_name.'|grep -v grep');
        $option_appname = $this->input->hasOption('appname') && $this->input->getOption('appname');
        $option_port = $this->input->hasOption('port') && $this->input->getOption('port');
        if($option_appname){
            // kill process by app name, multiple service items may have the same name
            $app_name = config('app_name');
            $pids = [];
            exec("ps -ef|grep '.$app_name.'|grep -v grep|awk '{print $2}'", $pids); // |xargs kill -9
            $pids = array_unique($pids);
            if(count($pids) == 0){
                stdLog()->warning('server not found');
                return;
            }
        }elseif ($option_port){
            // kill process by port, cannot be used in wsl
            $servers = config('server.servers');
            $port = $servers[0]['port'];
            exec("lsof -i:".$port."|awk '{if (NR>1){print $2}}'", $pids); // |xargs kill -9
            $pids = array_unique($pids);
            if(count($pids) == 0){
                stdLog()->warning('server not found');
                return;
            }
        } else{
            // default kill process by hyperf.pid
            $pids = getPids();
            if (count($pids) <= 1) {
                stdLog()->warning('server not found');
                return;
            }
        }
        $pids = implode(',', $pids);
        $ret = System::exec('which htop');
        if (empty($ret['output'])) {
            stdLog()->warning('htop命令行不存在，请先安装 yum install htop / apt install htop');
        } else {
            passthru("htop -tp $pids");
        }
    }
}
