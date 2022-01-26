<?php

declare(strict_types=1);

namespace lobtao\helper\commands;

use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Input\InputOption;

class ServerStopCommand extends HyperfCommand
{

    public function __construct()
    {
        parent::__construct('server:stop');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('server stop');
        $this->addOption('appname', '-a', InputOption::VALUE_NONE, 'kill process by app name');
        $this->addOption('port', '-p', InputOption::VALUE_NONE, 'kill process by port');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        $option_appname = $this->input->hasOption('appname') && $this->input->getOption('appname');
        $option_port = $this->input->hasOption('port') && $this->input->getOption('port');
        if($option_appname){
            // kill process by app name, multiple service items may have the same name
            $app_name = config('app_name');
            $pids = [];
            exec("ps -ef|grep '.$app_name.'|grep -v grep|awk '{print $2}'", $pids); // |xargs kill -9
            $pids = array_unique($pids);
            if(count($pids) == 0){
                stdLog()->info('server not exists');
                return;
            }
        }elseif ($option_port){
            // kill process by port, cannot be used in wsl
            $servers = config('server.servers');
            $port = $servers[0]['port'];
            exec("lsof -i:".$port."|awk '{if (NR>1){print $2}}'", $pids); // |xargs kill -9
            $pids = array_unique($pids);
            if(count($pids) == 0){
                stdLog()->info('server not exists');
                return;
            }
        } else{
            // default kill process by hyperf.pid
            $pids = getPids();
            if (count($pids) <= 1) {
                stdLog()->info('server not started');
                return;
            }
        }
        $pids = implode(' ', $pids);
        exec("kill -9 $pids");
        stdLog()->info('stop server success');
    }
}
