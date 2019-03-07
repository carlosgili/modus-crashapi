# modus-crashapi

##Crash Information API in PHP

This is a simple API implementation developed for ModusCreate in order to get
Crash Information from NHTSA (one.nhtsa.gov) in specific JSON Format.

The implementation is pretty simple and it does not offer security measures like
authentication, full request parameters validation, full UTF-8 multilanguage
compatibility checks, etc.

## Requirements

- A Clean Server with Debian 8
- Apache 2.4 or upper server with mod_php enabled
- PHP 5.6 or upper with JSON extension

## Optional Requirements

- Composer (http://getcomposer.org) (Only if you plan to Upgrade/Reinstall Flight)
- Flight PHP Framework (http://flightphp.com) (Already Bundled with the App)

## Directory Structure

```
 - vendor			Composer downloaded files (Flight Framework)
 - composer.json		Composer json configuration
 - composer.lock		Composer lock file
 - README.md			This readme file
 - html				The directory publised in the WEB Server
   - .htaccess			Apache Server configurations
   - index.php			Full Implementation
```

## Basic LAMP Setup on Debian

During the setup we assume that you have a newly deployed
Server or VM with Debian 8.

For all the setup operations you should be logged-in as 'root'

To setup the basic LAMP use:

```
# apt-get install php5-cli php5-json
# apt-get install apache2 libapache2-mod-php5
```

We do not require mysql.

To check if PHP is running you can use:

```
# php -r "print \"hello\n\";"
```

You shoud see a hello.

## Changing Apache to Port 8080

To change Apache server to Listen on port 8080, you must edit
/etc/apache2/ports.conf and change the line:

```
Listen 80
```

For the line:

```
Listen 8080
```

Then change the file /etc/apache2/sites-available/000-default.conf
and change the lines:

```
<VirtualHost *:80>
	....
	DocumentRoot /var/www/html
	....
```

For the lines:

```
<VirtualHost *:8080>
	....
	DocumentRoot /var/www/modus-crashapi/html
	....
```

## Downloading and Installing the Application under the Apache Server

Enter /var/www directory

```
# cd /var/www
```

We will require git to download the repository:

```
# apt-get install git
```

Clone the repository:

```
# git clone https://github.com/carlosgili/modus-crashapi.git
# chown -R www-data:www-data modus-crashapi
```

## Restart Apache and check the results

To restart Apache use:

```
# systemctl restart apache2
```

Now it should be able to access the server at port 8080:

First Install lynx:

```
# apt-get install lynx
```

Now check the if the server is running:

```
# lynx http://localhost:8080
```

You should get a JSON reponse simillar to:

```
{ "status": "Ready" }
```

If your server is accessible thru your network
you can access the server with any browser at:

```
http://<your_server_ip>:8080
```

## OPTIONAL: Composer and Flight Setup

First you MUST be inside /var/www/modus-crashapi directory

```
# cd /var/www/modus-crashapi
```

To install Composer use:

```
# php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
# php composer-setup.php
```

This should have created two new files:

```
composer-setup.php
composer.phar
```

To download and reinstall the Flight Framework you can use:

```
# php composer.phar require mikecao/flight
```

If you require aditional Information about the Composer installation
you can check:

http://getcomposer.com/download/

If tou require additional information about Flight setup you can
check:

http://flightphp.com/install/

