WebInterface
============

The Web Interface uses standard LAMP (Linux, Apache, MySQL, PHP) to provide a flexible and intuitive Web Interface for its products.

You may use this version of WebInterface. Please notice that it is in development and currenly very unstable.

to use this version, please copy the following lines to a linux terminal:

`sudo apt-get update sudo apt-get -y install apache2 php5 php5-mysql php5-snmp snmp snmpd vsftpd git`
`cd /var/www`
`git clone https://github.com/AYECKa/WebInterface.git`
`cd WebInterface`
`git checkout origin/development`

now you can go to your browser, and type `http://localhost/WebInterface`