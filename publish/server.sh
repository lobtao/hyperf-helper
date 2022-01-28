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

php ./bin/hyperf.php server:$1 $2
