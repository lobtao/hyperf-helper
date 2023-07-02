#!/bin/bash
# command list
# ./server.sh start
# ./server.sh start -d
# ./server.sh restart
# ./server.sh restart -d
# ./server.sh reload
# ./server.sh stop
# ./server.sh status

# start server with www
# sudo -H -u www ./server.sh start
# sudo -H -u www ./server.sh start -d
# sudo -H -u www ./server.sh restart
# sudo -H -u www ./server.sh restart -d
# sudo -H -u www ./server.sh reload
# sudo -H -u www ./server.sh stop
# sudo -H -u www ./server.sh status

# php执行文件的路径最好使用绝对路径,不受系统其它配置环境的影响
if [ "$1" = "status" ]
then
    php -d swoole.use_shortname='Off' ./bin/hyperf.php server:status
else
    sudo -H -u www php -d swoole.use_shortname='Off' ./bin/hyperf.php server:"$1" $2
fi

# swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php server:"$1" "$2"
