WebInterface
============

To install Ayecka Web Interface system on your Ubuntu machine, please follow the instructions bellow.  
This guide is also relevant to Linux board like Raspberry Pi, Beagleboard and more.

### Download

First of all, please make sure you have the latest version of Ayecka Web Interface.   
If you have a previous version, please download the latest version from github.

### Make your machine ready

To run Ayecka Web Interface system on UBUNTU OS, your machine requires the following extensions:

1. apache2

2. mysql-server

3. php5

4. php5-mysql

5. php5-snmp

6. snmp

7. snmpd

8. vsftpd

9. phpmyadmin

Note: Internet connection is required. 

To download and install the required extensions, follow the instructions below step by step.   
  

1. Open the Terminal software on your computer or connect via SSH protocol.

2. Update the resources list by copy the line below, paste it into the terminal and run by pressing "Enter".

`sudo apt-get update`

Note: The installation progress may take a few minutes.

3. Copy the line below, paste it into the terminal and run by pressing "Enter".

`sudo apt-get -y install apache2 php5 php5-mysql php5-snmp snmp snmpd vsftpd`

Note: The installation progress may take a few minutes.

4. Copy the line below, paste it into the terminal and run by pressing "Enter".  
During the installation, you will be requested to set up a password. The default password of the Ayecka Web Interface is "password", so it is recommended to put in this password.

`sudo apt-get -y install mysql-server`

5. Copy the line below, paste it into the terminal and run by pressing "Enter".  
During this step, you will be requested to enter a password (at least once) - please put in the same password as before.

`sudo apt-get -y install phpmyadmin`

6. Copy the line below, paste it into the terminal and run by pressing "Enter".

`sudo nano /etc/apache2/apache2.conf`

7. Text editor will show on your screen. Copy the line below, paste it into the text editor on the first line.  
To save the changes, click ctrl+x, confirm the action by clicking y and then press "Enter".

`Include /etc/phpmyadmin/apache.conf`

The file should look like this:

`Include /etc/phpmyadmin/apache.conf`

`#`
`# Based upon the NCSA server configuration files originally by Rob MCool.` 
`#`

8. Restart the apache server by copying the line below, pasting it into the terminal, and then run by pressing "Enter".

`sudo service apache2 restart`

9. Test your installation  
Enter to "[http://localhost][2]" on your browser. If you work remotely, enter the IP address of your machine. If you can see a page with words "It works!", then your installation was successful. If not, please check again the instructions in this guide.

### Install Ayecka Web Interface Files

1. Unzip downloaded file and choose a directory of your OS (Ubuntu - in this case).   
The directory contains 3 sub-directory:

2. Copy the sub-directory Content from the source directory to the destination directory on your machine according the table:   
If you are working remotely, use SFTP client software (Filezile, WinSCP) during this step.   

copy from `www/` to `/var/www/` and overwrite files

copy from `Configuration` to `/etc/php5/apache2` and overwrite the files

### Add Mysql Data

To finish the installation, you need to update the mysql data on your machine.

1. Enter to "[localhost/phpmyadmin][3]" in your browser.   
If you are working remotely, enter yourIPaddress/phpmyadmin (example: http://192.168.2.10/phpmyadmin).

2. Login as `root`. Enter the password you set before.

3. Select "Import" on the top menu. Select "Create_DB.sql" file from Mysql directory and click "Go".   
This action will create a new database for Ayecka system.

4. Click "Ayecka" on the databases list on the left.

5. Select "Import" in the top menu. Select "Insert_tables.sql" file from Mysql directory and click "Go".   
This action will add all ayecka data to the database.

6. Restart the apache server by copy the line below, paste it into the terminal and run by pressing enter.

sudo service apache2 restart

### Congratulations!

Now you have Ayecka Web Interface system on your machine.   
For use the system, enter to "[localhost][2]" and select your device. 

If you have any problem, please [contact us][4].

Use MIB2XML to convert MIB to

\- Replace the `"value="` to `"oid="` in the xml file.

\- save the xml file in `/var/lib/mysql/Ayecka`

\- goto [192.168.2.205/phpmyadmin][5]

  `username: root`

`password: password`

\- select db "Ayecka" in the left

\- click "SQL"

\- run this query to truncate the old values:

`truncate TABLENAME;`

(table name is replace with the relevant table like sr1mib)

\- click on "SQL"

\- run this query:

`LOAD XML INFILE 'SR1c.xml'  
INTO TABLE sr1mib  
ROWS IDENTIFIED BY '';`

[1]: http://www.ayecka.com/webinterface/download.php
[2]: http://localhost/
[3]: http://localhost/phpmyadmin
[4]: http://www.ayecka.com/webinterface/support.php
[5]: http://192.168.2.205/phpmyadmin
