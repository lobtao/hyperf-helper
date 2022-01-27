<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\Contract\SessionInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Server\ServerFactory;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

/*
 * composer.json 配置如下，实现自动加载
 *
 * "autoload": {
 * "psr-4": {
 * "App\\": "app/"
 * },
 * "files": [
 * "app/Functions.php"
 * ]
 * },
 *
 */

if (!function_exists('container')) {
    /**
     * 容器实例.
     */
    function container(): ContainerInterface
    {
        return ApplicationContext::getContainer();
    }
}

if (!function_exists('redis')) {
    /**
     * redis 客户端实例.
     * @return \Hyperf\Redis\Redis|mixed
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function redis()
    {
        return container()->get(Hyperf\Redis\Redis::class);
    }
}

if (!function_exists('server')) {
    /**
     * server 实例 基于 swoole server.
     * @return Server|\Swoole\Coroutine\Server
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function server()
    {
        return container()->get(ServerFactory::class)->getServer()->getServer();
    }
}

if (!function_exists('frame')) {
    /**
     * websocket frame 实例.
     * @return Frame|mixed
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function frame()
    {
        return container()->get(Frame::class);
    }
}

if (!function_exists('websocket')) {
    /**
     * websocket 实例.
     * @return mixed|WebSocketServer
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function websocket()
    {
        return container()->get(WebSocketServer::class);
    }
}

if (!function_exists('cache')) {
    /**
     * 缓存实例 简单的缓存.
     * @return CacheInterface|mixed
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function cache()
    {
        return container()->get(Psr\SimpleCache\CacheInterface::class);
    }
}

if (!function_exists('stdLog')) {
    /**
     * 向控制台输出日志.
     * @return mixed|StdoutLoggerInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function stdLog()
    {
        return container()->get(StdoutLoggerInterface::class);
    }
}

if (!function_exists('logger')) {
    /**
     * 向日志文件记录日志.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function logger(): LoggerInterface
    {
        return container()->get(LoggerFactory::class)->make();
    }
}

if (!function_exists('request')) {
    /**
     * 请求对象
     * @return mixed|RequestInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function request()
    {
        return container()->get(RequestInterface::class);
    }
}

if (!function_exists('response')) {
    /**
     * 请求回应对象
     * @return mixed|ResponseInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function response()
    {
        return container()->get(ResponseInterface::class);
    }
}

if (!function_exists('session')) {
    /**
     * session 对象
     * @return mixed|SessionInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function session()
    {
        return container()->get(SessionInterface::class);
    }
}

if (!function_exists('event')) {
    /**
     * event 事件对象
     * @return EventDispatcherInterface|mixed
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function event()
    {
        return container()->get(EventDispatcherInterface::class);
    }
}

if (!function_exists('lang')) {
    /**
     * lang 多语言翻译对象
     * @return mixed|TranslatorInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function lang()
    {
        return container()->get(TranslatorInterface::class);
    }
}

if (!function_exists('validator')) {
    /**
     * validator 验证器.
     * @return mixed|ValidatorFactoryInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    function validator()
    {
        return container()->get(ValidatorFactoryInterface::class);
    }
}

if (!function_exists('getPids')) {
    /**
     * 获取服务所有pids.
     * @return array
     */
    function getPids(): array
    {
        $pids = [];
        $master_pid = '';
        // 获取master pid
        $pid_file = BASE_PATH . '/runtime/hyperf.pid';
        if (file_exists($pid_file)) {
            $master_pid = file_get_contents(BASE_PATH . '/runtime/hyperf.pid');
            $pids[] = $master_pid;
        }
        if ($master_pid) {
            // 获取manager pid
            $result = \Swoole\Coroutine\System::exec("ps -eLf|grep $master_pid|grep -v grep|awk '{print $2}'");
            $result = trim($result['output']);
            $result = strlen($result) > 0 ? explode(PHP_EOL, $result) : [];
            foreach ($result as $value) {
                if ($master_pid != $value) {
                    // 获取manager创建的worker、task等工作进程pid
                    $tmp = \Swoole\Coroutine\System::exec("ps -eLf|grep $value|grep -v grep|awk '{print $2}'");
                    $tmp = trim($tmp['output']);
                    $tmp = explode(PHP_EOL, $tmp);
                    $pids = array_merge($pids, $tmp);
                }
            }
        }
        // 进程号去重
        return array_unique($pids);
    }
}

if (!function_exists('getMasterPid')) {
    /**
     * 获取服务master pid.
     * @return string
     */
    function getMasterPid(): string
    {
        $master_pid = '';
        // 获取master pid
        $pid_file = BASE_PATH . '/runtime/hyperf.pid';
        if (file_exists($pid_file)) {
            $master_pid = file_get_contents(BASE_PATH . '/runtime/hyperf.pid');
        }
        return $master_pid;
    }
}
