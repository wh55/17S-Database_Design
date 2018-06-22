How to Test or Install:

1) To run the system, you need an environment that supports PHP and mySQL. 

2) Upload all the pages in “library” folder to the appropriate directory of your web space.
If test using local host, find the appropriate document host directory of your local environment that supports PHP, and copy all the pages in “library” to it.

3) Database file provided as librarydb.sql, which can be found in this “readme” folder. Import this file into the database. You’ll have a database named ”libraraydb”, and all the tables needed in it.

4) Edit “config.php” page to set correct preference to connect to the database.

You’ll find in this php file:

DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', '');
DEFINE ('DB_NAME', 'librarydb');

- localhost is where you put all the php files, if you upload files on a web server, you should change it to the directory address to where you upload them
-  root is your database user name, change it to your own datatbase user name
-  the default password is empty, if your database needs a password, enter it between the quotes ' ' after ’DB_PASSWORD',
-  libraraydb is the name of your database, change it if you have a different database name

5) Test and use the system in a web browser.
eg. If test in the localhost, you could go to http://localhost/ or http://localhost/index.php
      If upload to web space, you could go to http://“yourwebdirectoryhere”/ or http://“yourwebdirectoryhere”/index.php  (you should change “yourwebdirectoryhere” to the appropriate address.)
    If upload the whole “library” folder, go to http://localhost/library/ or http://localhost/library/index.php for localhost
    and http://“yourwebdirectory”/library/ or http://“yourwebdirectory”/library/index.php for web space


N.B.)
Technical dependencies:
MySQL
PHP
HTML
CSS
JavaScript
