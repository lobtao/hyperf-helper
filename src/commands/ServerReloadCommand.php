<?php

declare(strict_types=1);

namespace lobtao\helper\commands;

use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Swoole\Coroutine\System;
use Swoole\Process;
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
        $this->addOption('task', '-t', InputOption::VALUE_NONE, 'safe reload task only');
        $this->addOption('default', '-d', InputOption::VALUE_NONE, 'safe reload worker&task');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        $option_task = $this->input->hasOption('task') && $this->input->getOption('task');

        $master_pid = getMasterPid();
        if(empty($master_pid)){
            stdLog()->warning("server pid:{$master_pid} not found");
            return;
        }

        if($option_task){
            // reload task process
            Process::kill(intval($master_pid), SIGUSR2); // reload task
            stdLog()->info('reload task process success');
        } else{
            // reload worker&task process
            Process::kill(intval($master_pid), SIGUSR1); // reload worker&task
            stdLog()->info('reload worker&task process success');
        }
    }
}
