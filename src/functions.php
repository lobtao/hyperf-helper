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

if (! function_exists('container')) {
    /**
     * 容器实例.
     */
    function container(): ContainerInterface
    {
        return ApplicationContext::getContainer();
    }
}

if (! function_exists('redis')) {
    /**
     * redis 客户端实例.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return \Hyperf\Redis\Redis|mixed
     */
    function redis()
    {
        return container()->get(Hyperf\Redis\Redis::class);
    }
}

if (! function_exists('server')) {
    /**
     * server 实例 基于 swoole server.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return Server|\Swoole\Coroutine\Server
     */
    function server()
    {
        return container()->get(ServerFactory::class)->getServer()->getServer();
    }
}

if (! function_exists('frame')) {
    /**
     * websocket frame 实例.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return Frame|mixed
     */
    function frame()
    {
        return container()->get(Frame::class);
    }
}

if (! function_exists('websocket')) {
    /**
     * websocket 实例.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return mixed|WebSocketServer
     */
    function websocket()
    {
        return container()->get(WebSocketServer::class);
    }
}

if (! function_exists('cache')) {
    /**
     * 缓存实例 简单的缓存.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return CacheInterface|mixed
     */
    function cache()
    {
        return container()->get(Psr\SimpleCache\CacheInterface::class);
    }
}

if (! function_exists('stdLog')) {
    /**
     * 向控制台输出日志.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return mixed|StdoutLoggerInterface
     */
    function stdLog()
    {
        return container()->get(StdoutLoggerInterface::class);
    }
}

if (! function_exists('logger')) {
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

if (! function_exists('request')) {
    /**
     * 请求对象
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return mixed|RequestInterface
     */
    function request()
    {
        return container()->get(RequestInterface::class);
    }
}

if (! function_exists('response')) {
    /**
     * 请求回应对象
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return mixed|ResponseInterface
     */
    function response()
    {
        return container()->get(ResponseInterface::class);
    }
}

if (! function_exists('session')) {
    /**
     * session 对象
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return mixed|SessionInterface
     */
    function session()
    {
        return container()->get(SessionInterface::class);
    }
}

if (! function_exists('event')) {
    /**
     * event 事件对象
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return EventDispatcherInterface|mixed
     */
    function event()
    {
        return container()->get(EventDispatcherInterface::class);
    }
}

if (! function_exists('lang')) {
    /**
     * lang 多语言翻译对象
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return mixed|TranslatorInterface
     */
    function lang()
    {
        return container()->get(TranslatorInterface::class);
    }
}

if (! function_exists('validator')) {
    /**
     * validator 验证器.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return mixed|ValidatorFactoryInterface
     */
    function validator()
    {
        return container()->get(ValidatorFactoryInterface::class);
    }
}

if (! function_exists('getPids')) {
    /**
     * validator 获取服务所有pids.
     * @return array
     */
    function getPids(): array
    {
        // 根据hyperf.pid进程号终止
        $pids = [];
        // 获取master pid
        $pid = file_get_contents(BASE_PATH . '/runtime/hyperf.pid');
        $pids[] = $pid;
        $result = [];
        // 获取manager pid
        exec("ps -eLf|grep $pid|grep -v 'grep'|awk '{print $2}'", $result);
        foreach ($result as $value) {
            if ($pid != $value) {
                // 获取manager创建的worker、task等工作进程pid
                $result = exec("ps -eLf|grep $value|grep -v 'grep'|awk '{print $2}'", $pids);
            }
        }
        // 进程号去重
        return array_unique($pids);
    }
}
