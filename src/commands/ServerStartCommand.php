<?php

declare(strict_types=1);

namespace lobtao\helper\commands;

use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Input\InputOption;

class ServerStartCommand extends HyperfCommand
{

    public function __construct()
    {
        parent::__construct('server:start');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('server start/start -d');
        $this->addOption('daemonize', '-d', InputOption::VALUE_NONE, 'daemonize mode');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        $option_daemonize = $this->input->hasOption('daemonize')?$this->input->getOption('daemonize'):false;
        putenv('DAEMONIZE=' . json_encode($option_daemonize));
        if ($option_daemonize) {
            passthru('php ' . BASE_PATH . '/bin/hyperf.php start > /dev/null &');
            stdLog()->info('server start success');
        } else {
            // stdLog()->info('when this mode is started, there is no highlight color on the console');
            stdLog()->info('debug mode please use `php ./bin/hyperf.php start`');
            stdLog()->info('daemonize mode please use `php ./bin/hyperf.php server:start -d`');
            // echo PHP_EOL;
            // passthru('php ' . BASE_PATH . '/bin/hyperf.php start');
        }
    }
}