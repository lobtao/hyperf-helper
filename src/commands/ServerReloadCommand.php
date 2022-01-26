<?php

declare(strict_types=1);

namespace lobtao\helper\commands;

use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Input\InputOption;

class ServerReloadCommand extends HyperfCommand
{

    public function __construct()
    {
        parent::__construct('server:reload');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('server reload');
        $this->addOption('task', '-t', InputOption::VALUE_NONE, 'reload task');
        $this->addOption('worker', '-w', InputOption::VALUE_NONE, 'reload worker');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        $option_task = $this->input->hasOption('task') && $this->input->getOption('task');
        $option_worker = $this->input->hasOption('worker') && $this->input->getOption('worker');

        $master_pid = getMasterPid();
        if(empty($master_pid)){
            stdLog()->warning('server not found');
            return;
        }
        if($option_task){
            // reload task process
            exec("kill -USR2 $master_pid");
            stdLog()->info('reload task process success');
        }elseif ($option_worker){
            // reload worker process
            exec("kill -USR1 $master_pid");
            stdLog()->info('reload worker process success');
        } else{
            // reload worker&task process
            exec("kill -USR1 $master_pid");
            exec("kill -USR2 $master_pid");
            stdLog()->info('reload worker&task process success');
        }
    }
}
