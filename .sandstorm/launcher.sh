#!/bin/bash

# Create a bunch of folders under the clean /var that php, nginx, and mysql expect to exist
mkdir -p /var/lib/mysql
mkdir -p /var/lib/mysql-files
mkdir -p /var/lib/nginx
mkdir -p /var/lib/php/sessions
mkdir -p /var/log
mkdir -p /var/log/mysql
mkdir -p /var/log/nginx
# Wipe /var/run, since pidfiles and socket files from previous launches should go away
# TODO someday: I'd prefer a tmpfs for these.
rm -rf /var/run
mkdir -p /var/run/php
rm -rf /var/tmp
mkdir -p /var/tmp
mkdir -p /var/run/mysqld

# Rotate log files larger than 512K
log_files="$(find /var/log -type f -name '*.log')"
for f in $log_files; do
    if [ $(du -b "$f" | awk '{print $1}') -ge $((512 * 1024)) ] ; then
        mv $f $f.1
    fi
done

# Ensure mysql tables created
# HOME=/etc/mysql /usr/bin/mysql_install_db
HOME=/etc/mysql /usr/sbin/mysqld --initialize

# Spawn mysqld, php
HOME=/etc/mysql /usr/sbin/mysqld --skip-grant-tables &
/usr/sbin/php-fpm7.4 --nodaemonize --fpm-config /etc/php/7.4/fpm/php-fpm.conf &
# Wait until mysql and php have bound their sockets, indicating readiness
while [ ! -e /var/run/mysqld/mysqld.sock ] ; do
    echo "waiting for mysql to be available at /var/run/mysqld/mysqld.sock"
    sleep .2
done
if [ ! -e /var/.db-created ]; then
    mysql --user root -e 'CREATE DATABASE app'
    mysql --user root --database app < /opt/app/install_into_db.sql
	mysql --user root --database app < /opt/app/install_into_db_sync.sql
    touch /var/.db-created
fi

while [ ! -e /var/run/php/php7.4-fpm.sock ] ; do
    echo "waiting for php-fpm7.4 to be available at /var/run/php/php7.4-fpm.sock"
    sleep .2
done

# Start nginx.
/usr/sbin/nginx -c /opt/app/.sandstorm/service-config/nginx.conf -g "daemon off;"
