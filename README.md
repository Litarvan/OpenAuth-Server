![OpenAuth Logo](http://image.noelshack.com/fichiers/2015/20/1431453946-banierreoauth.png)

# What is it ?
The server is an Yggdrasil server to use with the OpenAuth client (but works with other yggdrasil clients)  

/!\ The OpenAuth Server 1.0 table does not work with OpenAuth Server 2.0 /!\  

/!\ You now have to use the email instead of the username to authenticate and signout. /!\  
/!\ In every payload that require "username", put here the email instead /!\  

# Installation
There is two different way to install the OpenAuth-Server.  

### 1 - Installer.php
Just download the installer.php and upload it to your remote directory.  
Go to your page http://yoursite/openauth-server/installer.php, let him do the job and follow the instructions.  
Then, when you see many unreadable text and the loading is finished, you can got to http://yoursite/openauth-server/ and follow the database configuration  
/!\ May not works on your host because the script take a long time to execute /!\  

### 2 - Manually
You need to have PHP and Composer installed on your remote host or local host.  
Unzip all the files that are in the "server" directory.  
Open a terminal at the root directory of OpenAuth-Server and just do if you have composer installed globally  
```shell
composer update
```
or download the latest composer.phar at https://getcomposer.org/ and move him to the root directory, where the composer.json is located and run  
```shell
php composer.phar update
```
Then, when you see many unreadable text and the loading is finished, you can got to http://yoursite/openauth-server/ and follow the database configuration  

# The API  
The API is the same as the previous version or the same as Mojang Yggdrasil server.  
To register, send a POST JSON request to http://yoursite/openauth-server/api/register.  
```json
{
    "username": "you_username",
    "password": "your_password_",
    "verification_password": "your_password",
    "email": "youremail@something.com",
    "key": "your_private_key"
}
```
You can found your private key in your config/settings.php. It is automatically generated when you complete the installation.  
