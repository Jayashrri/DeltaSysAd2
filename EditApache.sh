#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
cd $DIR

mkdir -p /var/www/html/node1.com/public_html
cp Node1.php /var/www/html/node1.com/public_html/index.php
chown -R www-data:www-data /var/www/html/node1.com

mkdir -p /var/www/html/node2.com/public_html
cp Node2.php /var/www/html/node2.com/public_html/index.php
chown -R www-data:www-data /var/www/html/node2.com

mkdir -p /var/www/html/node3.com/public_html
cp Node3.php /var/www/html/node3.com/public_html/index.php
chown -R www-data:www-data /var/www/html/node3.com

mkdir -p /var/www/html/node4.com/public_html
cp Node4.php /var/www/html/node4.com/public_html/index.php
chown -R www-data:www-data /var/www/html/node4.com

mkdir -p /var/www/html/loadbalancer.com/public_html
cp LoadBalancer.html /var/www/html/loadbalancer.com/public_html/index.html
cp cernserver.php /var/www/html/loadbalancer.com/public_html
chown -R www-data:www-data /var/www/html/loadbalancer.com

chmod -R 755 /var/www/html

cd /etc/apache2/sites-available
a2dissite 000-default.conf

cpath="node1.com.conf"
touch $cpath
echo "<VirtualHost *:81>
 
ServerAdmin admin@node1.com
ServerName  node1.com
DocumentRoot /var/www/html/node1.com/public_html
 
ErrorLog \${APACHE_LOG_DIR}/node1.com_error.log
CustomLog \${APACHE_LOG_DIR}/node1.com_access.log combined
 
</VirtualHost>" >$cpath


cpath="node2.com.conf"
touch $cpath
echo "<VirtualHost *:82>
 
ServerAdmin admin@node2.com
ServerName  node2.com
DocumentRoot /var/www/html/node2.com/public_html
 
ErrorLog \${APACHE_LOG_DIR}/node2.com_error.log
CustomLog \${APACHE_LOG_DIR}/node2.com_access.log combined
 
</VirtualHost>" >$cpath


cpath="node3.com.conf"
touch $cpath
echo "<VirtualHost *:83>
 
ServerAdmin admin@node3.com
ServerName  node3.com
DocumentRoot /var/www/html/node3.com/public_html
 
ErrorLog \${APACHE_LOG_DIR}/node3.com_error.log
CustomLog \${APACHE_LOG_DIR}/node3.com_access.log combined
 
</VirtualHost>" >$cpath


cpath="node4.com.conf"
touch $cpath
echo "<VirtualHost *:84>
 
ServerAdmin admin@node4.com
ServerName  node4.com
DocumentRoot /var/www/html/node4.com/public_html
 
ErrorLog \${APACHE_LOG_DIR}/node4.com_error.log
CustomLog \${APACHE_LOG_DIR}/node4.com_access.log combined
 
</VirtualHost>" >$cpath


cpath="loadbalancer.com.conf"
touch $cpath
echo "<VirtualHost *:85>
 
ServerAdmin admin@loadbalancer.com
ServerName  loadbalancer.com
DocumentRoot /var/www/html/loadbalancer.com/public_html
 
ErrorLog \${APACHE_LOG_DIR}/loadbalancer.com_error.log
CustomLog \${APACHE_LOG_DIR}/loadbalancer.com_access.log combined
 
</VirtualHost>" >$cpath


a2ensite node1.com.conf
a2ensite node2.com.conf
a2ensite node3.com.conf
a2ensite node4.com.conf
a2ensite loadbalancer.com.conf

cd /etc/apache2
cpath="ports.conf";
echo "Listen 81
Listen 82
Listen 83
Listen 84
Listen 85" >$cpath

/etc/init.d/apache2 restart