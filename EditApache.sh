#!/bin/bash
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
cp CernServer.php /var/www/html/loadbalancer.com/public_html
chown -R www-data:www-data /var/www/html/loadbalancer.com

chmod -R 755 /var/www/html

cd /etc/apache2/sites-available
a2dissite 000-default.conf

$confpath = "node1.com.conf"
touch $confpath
echo "<VirtualHost *:81>
 
ServerAdmin admin@node1.com
ServerName  node1.com
DocumentRoot /var/www/html/node1.com
 
ErrorLog \${APACHE_LOG_DIR}/node1.com_error.log
CustomLog \${APACHE_LOG_DIR}/node1.com_access.log combined
 
</VirtualHost>" >$confpath


$confpath = "node2.com.conf"
touch $confpath
echo "<VirtualHost *:82>
 
ServerAdmin admin@node2.com
ServerName  node2.com
DocumentRoot /var/www/html/node2.com
 
ErrorLog \${APACHE_LOG_DIR}/node2.com_error.log
CustomLog \${APACHE_LOG_DIR}/node2.com_access.log combined
 
</VirtualHost>" >$confpath


$confpath = "node3.com.conf"
touch $confpath
echo "<VirtualHost *:83>
 
ServerAdmin admin@node3.com
ServerName  node3.com
DocumentRoot /var/www/html/node3.com
 
ErrorLog \${APACHE_LOG_DIR}/node3.com_error.log
CustomLog \${APACHE_LOG_DIR}/node3.com_access.log combined
 
</VirtualHost>" >$confpath


$confpath = "node4.com.conf"
touch $confpath
echo "<VirtualHost *:84>
 
ServerAdmin admin@node4.com
ServerName  node4.com
DocumentRoot /var/www/html/node4.com
 
ErrorLog \${APACHE_LOG_DIR}/node4.com_error.log
CustomLog \${APACHE_LOG_DIR}/node4.com_access.log combined
 
</VirtualHost>" >$confpath


$confpath = "loadbalancer.com.conf"
touch $confpath
echo "<VirtualHost *:80>
 
ServerAdmin admin@loadbalancer.com
ServerName  loadbalancer.com
DocumentRoot /var/www/html/loadbalancer.com
 
ErrorLog \${APACHE_LOG_DIR}/loadbalancer.com_error.log
CustomLog \${APACHE_LOG_DIR}/loadbalancer.com_access.log combined
 
</VirtualHost>" >$confpath


a2ensite node1.conf
a2ensite node2.conf
a2ensite node3.conf
a2ensite node4.conf
a2ensite loadbalancer.conf

cd /etc/apache2
$confpath = "ports.conf";
echo "Listen 81
Listen 82
Listen 83
Listen 84" >$confpath

etc/init.d/apache2 restart