#!/bin/bash
# command list
# ./server.sh start -d
# ./server.sh restart
# ./server.sh reload
# ./server.sh stop
# ./server.sh status

# start server with www
# sudo -H -u www ./server.sh start -d
# sudo -H -u www ./server.sh restart
# sudo -H -u www ./server.sh reload
# sudo -H -u www ./server.sh stop
# sudo -H -u www ./server.sh status

# php执行文件的路径最好使用绝对路径,不受系统其它配置环境的影响
export DAEMONIZE=false

# start
if [ "$1" == "start" ]
then
  if [ "$2" == "-d" ]
  then
    export DAEMONIZE=true
    echo "start daemonize..."
  fi
  php ./bin/hyperf.php start
  exit 0
fi

# restart
if [ "$1" == "restart" ]
then
  php ./bin/hyperf.php server:stop
  if [ "$2" == "-d" ]
  then
    export DAEMONIZE=true
    echo "start daemonize..."
  fi
  php ./bin/hyperf.php start
  exit 0
fi

# stop status reload
php ./bin/hyperf.php server:"$1"

# swoole-cli ./bin/hyperf.php server:$1 $2
