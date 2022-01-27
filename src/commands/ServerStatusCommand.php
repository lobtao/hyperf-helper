<?php

declare(strict_types=1);

namespace lobtao\helper\commands;

use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Swoole\Coroutine\System;
use Swoole\Process;
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
        $this->addOption('appname', '-a', InputOption::VALUE_NONE, 'view process status by app name');
        $this->addOption('port', '-p', InputOption::VALUE_NONE, 'view process status by port');
        $this->addOption('default', '-d', InputOption::VALUE_NONE, 'view process status by master pid');
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
            $result = System::exec("ps -ef|grep '.$app_name.'|grep -v grep|awk '{print $2}'"); // |xargs kill -9
            $pids = trim($result['output']);
            if(empty($pids)){
                stdLog()->warning('server not found');
                return;
            }
            $pids = explode(PHP_EOL, $pids);
        }elseif ($option_port){
            // kill process by port, cannot be used in wsl
            $servers = config('server.servers');
            $port = $servers[0]['port'];
            $result = System::exec("lsof -i:".$port."|awk '{if (NR>1){print $2}}'"); // |xargs kill -9
            $pids = trim($result['output']);
            if(empty($pids)){
                stdLog()->warning('server not found');
                return;
            }
            $pids = explode(PHP_EOL, $pids);
        } else{
            // default kill process by hyperf.pid master pid
            $master_pid = getMasterPid();
            if(!empty($master_pid) && Process::kill(intval($master_pid), 0)){
                $pids = getPids();
            }else{
                stdLog()->warning("server not found by master pid $master_pid");
                return;
            }
        }
        $pids = implode(',', $pids);
        $ret = System::exec('which htop'); // 1.htop 2.top
        if (empty(trim($ret['output']))) {
            passthru("top -p $pids");
        } else {
            passthru("htop -tp $pids");
        }
    }
}
