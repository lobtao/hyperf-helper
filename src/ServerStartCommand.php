<?php

declare(strict_types=1);

namespace lobtao\helper;

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
        $this->addOption('DAEMONIZE', '-d', InputOption::VALUE_NONE, '是否优化');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        $DAEMONIZE = $this->input->getOption('DAEMONIZE');
        putenv('DAEMONIZE=' . json_encode($DAEMONIZE));
        if ($DAEMONIZE) {
            passthru('php ' . BASE_PATH . '/bin/hyperf.php start > /dev/null &');
            stdLog()->info('启动服务成功');
        } else {
            stdLog()->info('该方式启动，控制台无高亮颜色');
            stdLog()->info('调试模式请使用 php ./bin/hyperf.php start');
            stdLog()->info('常驻模式请使用 php ./bin/hyperf.php server:start -d');
            echo PHP_EOL;
            passthru('php ' . BASE_PATH . '/bin/hyperf.php start');
        }
    }
}
