<?php

declare(strict_types=1);

namespace lobtao\helper\commands;

use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Swoole\Coroutine\System;
use Swoole\Process;
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
        $this->addOption('appname', 'a', InputOption::VALUE_NONE, 'force stop process by app name');
        $this->addOption('port', 'p', InputOption::VALUE_NONE, 'force stop process by port');
        $this->addOption('force', 'f', InputOption::VALUE_NONE, 'force stop process by master pid');
        $this->addOption('default', 'd', InputOption::VALUE_NONE, 'safe stop process by master pid');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        $php_path = getPhpPath();
        $option_appname = $this->input->hasOption('appname') && $this->input->getOption('appname');
        $option_port = $this->input->hasOption('port') && $this->input->getOption('port');
        $option_force = $this->input->hasOption('force') && $this->input->getOption('force');
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
            $pids = implode(' ', $pids);
            System::exec("kill -9 $pids");
            stdLog()->info("force stop server success by app name `$app_name`");
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
            $pids = implode(' ', $pids);
            System::exec("kill -9 $pids");
            stdLog()->info("force stop server success by port `$port`");
        } else{
            // default kill process by hyperf.pid
            $master_pid = getMasterPid();
            if (empty($master_pid) || !Process::kill(intval($master_pid), 0)) {
                stdLog()->warning("server not found");
            } else {
                if ($option_force) {
                    Process::kill(intval($master_pid), SIGKILL); // force stop
                } else {
                    Process::kill(intval($master_pid), SIGTERM); // safe stop
                }
                //等待5秒
                $time = time();
                while (true) {
                    usleep(1000);
                    if (!Process::kill(intval($master_pid), 0)) {
                        stdLog()->info(($option_force?'force':'safe')." stop server success by master pid {$master_pid}");
                        break;
                    } else {
                        if (time() - $time > 15) {
                            stdLog()->warning("stop server fail for pid:{$master_pid} , try `{$php_path} ./bin/hyperf.php server:stop -f` or `./server.sh stop -f` again");
                            break;
                        }
                    }
                }
            }
        }

        return 0;
    }
}
