OpenBiblio_EAL_Setup
====================

This is a small guide on how to set up OpenBiblio for EAL(Erhvervsakademiet Lillebælt) from Odense, Denmark. 
The main reason for this guide are some hardcoded modifications that adjusted the way barcode-labels are printed so that it would suit our printer requirements.

The modifications made are probably not the best but they just work so we will be satisfied with a hardcoded solution for now.

Installation on/with : 
- linux server running apache2, php5, mysql
- phpmyadmin (Optional)
- Zebra label printer GX430t


###1. Download latest build
http://sourceforge.net/projects/obiblio/files/latest/download

###2. Extract to htdocs/webroot
tar -xf openbiblio-0.7.1.tar.gz -C /var/www/

###3. Set up database, tables and user
You can either do this via phpmyadmin or via the command line. I did it in both ways, but i will just show the commands i used in the terminal.

######Create database
mysql> create database OpenBiblio /*!40100 default character set latin1 */;

######Verify database
mysql> show databases;

######You should see something like this:
+--------------+
| Database     |
+--------------+
| mysql        |
| OpenBiblio   |
+--------------+

######Create new mysql user and grant privileges on the openbiblio database
mysql> grant all privileges on OpenBiblio.* to obiblio_user@localhost identified by 'obiblio_password';

######Verify user and database
mysql -u obiblio_user -p obiblio_password OpenBiblio

######Modify database credentials in the database constants file (openbiblio/database_constants.php)
nano openbiblio/database_constants.php

######Create database tables
Accessing http://localhost/openbiblio/install/index.php in your browser will automatically run a php script that will create all necesary tables

###4. Set up new admin

Go to http://localhost/openbiblio/index.php . The default username and password will be both "admin". After logging in, go to "Admin" tab -> staff list and create new user.


###5. Modify label printing 

There are only 2 files that need to be modified, this being the reason for not uploading entire OpenBiblio source code.

The first one is layout.php (/var/www/openbiblio/shared/layout.php) which choose the PDF layout when we are generating reports. You can find it in the repository here - https://github.com/victor1tnet/OpenBiblio_EAL_Setup/blob/master/modified-layout.php

The second file is A4_barcode_1x16.php(/var/www/openbiblio/layouts/default/A4_barcode_1x16.php).This is the PDF layout(template) file that will display our labels nicely. You can find it in the repository here - https://github.com/victor1tnet/OpenBiblio_EAL_Setup/blob/master/modified-A4_barcode_1x16.php

For the original files check the other 2 files in the repository.

###6. Tips/warnings

####6.1. Strange error when trying to check out item

When the everything is being set up, OpenBiblio/Apache/PHP can sometimes "confuse" the time you want to use. Or better yet, it doesn't know what time to use. Make sure you have a defined timezone in your php.ini file

Modify php.ini
- sudo nano /etc/php5/apache2/php.ini
Look for a line containing "date.timezone" 
- press "ctrl+w" now type "date.timezone" and now hit Enter
You will probably find something like this:
- ;date.timezone = 
Uncomment the line and add your time zone in the following fashion:
- date.timezone = "Europe/Copenhagen"
Save file and restart apache
- ctrl+x
- sudo /etc/init.d/apache2 restart

####6.2. Security tip 

Remove the openbiblio/install directory completely to prevent unauthorized use of install or upgrade tools.

####6.3. Security tip 

Verify that the display_errors setting in php.ini is 'Off' to prevent unintended information disclosure.


##Resources
- http://openbiblio.sint-godelieve-instituut.be/install_instructions.html
- http://sourceforge.net/projects/obiblio/
