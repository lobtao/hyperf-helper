<?php

declare(strict_types=1);

namespace lobtao\helper\commands;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Server\Command\StartServer;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServerStartCommand extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct('server:start');
    }

    protected function configure()
    {
        $this->setDescription('server start')
            ->addOption('daemonize', 'd', InputOption::VALUE_NONE, 'daemonize mode');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $option_daemonize = $input->hasOption('daemonize') ? $input->getOption('daemonize') : false;
        $config = $this->container->get(ConfigInterface::class);
        
        if($option_daemonize){
            $config->set('server.settings.daemonize', true);
            stdLog()->info('start daemonize...');
        }
        $start_server = make(StartServer::class);
        $start_server->execute($input, $output);

        return 0;
    }

}
