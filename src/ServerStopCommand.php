<?php

declare(strict_types=1);

namespace lobtao\helper;

use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        // exec("lsof -i:9502|awk '{if (NR>1){print $2}}'|xargs kill -9");
        // ps aux|grep hyperf|awk '{if (NR>0){print $2}}'|xargs kill -9

        // 根据进程名称停止，有缺陷，可能多个服务项目名称一样
        // $app_name = config('app_name');
        // exec("ps -ef|grep '.$app_name.'|grep -v grep|awk '{print $2}'|xargs kill -9");

        // 根据端口号停止，有缺陷，在wsl查找不到进程
        // $servers = config('server.servers');
        // $port = $servers[0]['port'];
        // exec("lsof -i:".$port."|awk '{if (NR>1){print $2}}'"); // |xargs kill -9
        // stdLog()->info('停止服务成功');

        // 根据hyperf.pid进程号终止
        $pids = getPids();
        if (count($pids) == 1) {
            stdLog()->info('服务没有运行');
            return;
        }
        $pids = implode(' ', $pids);
        exec("kill -9 $pids");
        stdLog()->info('服务停止成功');
    }
}
