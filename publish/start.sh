# normal
# ./start.sh

# start server with www
# sudo -H -u www ./start.sh

# php执行文件的路径最好使用绝对路径,不受系统其它配置环境的影响
php -d swoole.use_shortname='Off' ./bin/hyperf.php start
# swoole-cli -d swoole.use_shortname='Off' ./bin/hyperf.php start
