# modus-crashapi

Crash Information API in PHP

This is a simple API implementation developed for ModusCreate in order to get
Crash Information from NHTSA (one.nhtsa.gov) in specific JSON Format.

The implementation is pretty simple and it does not offer security measures like
authentication, full request parameters validation, full UTF-8 multilanguage
compatibility checks, etc.

## Requirements

- Composer (http://getcomposer.org) (Optional to Upgrade/Reinstall Flight)
- Flight PHP Framework (http://flightphp.com) (Already Bundled with the App)
- Apache 2.4 or upper server with mod_php enabled
- PHP 5.6 or upper with CURL and JSON extensions

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

The Apache Server must be enabled to accept .htaccess files to Override
configurations, you can check .htaccess file inside public_html.

## Installation on Apache Server

The simplest installation method is to install it in a newly
installed Server with an Standard and Basic LAMP setup:

1. Enter /var/www directory

```
# cd /var/www
```

2. Clone the repository:

```
# git clone https://cgili@bitbucket.org/cgili/modus-crashapi.git
```

3. Restart Apache2:

```
# service apache2 restart
```

## Composer and Flight Setup

First you MUST be inside /var/www directory

```
# cd /var/www
```

Then you need to install Composer as detailed details on their site at:
http://getcomposer.com/download/

```
# php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
# php composer-setup.php
```

To download and reinstall the Flight Framework you can check their
site at http://flightphp.com/install/:

```
# php composer.phar require mikecao/flight
```

## Basic LAMP Setup on Debian/Ubuntu

For a basic setup of a LAMP on a newly deployed server use:

```
# apt-get install php5-cli php5-curl php5-json
# apt-get install apache2 libapache2-mod-php
```

This application does not require the installation of MySQL so
we skipped that setup.

##Changing Apache to Port 8080

I you want Apache server to Listen on port 8080, you must edit
/etc/apache2/ports.conf and change the line:

```
Listen 0.0.0.0:80
```

For the line:

```
Listen 0.0.0.0:8080
```

And finaly change the file /etc/apache2/sites-available/default.conf
and change the line:

```
<VirtualHost *:80>
```

For the line:

```
<VirtualHost *:8080>
```

Finally you must restart Apache:

```
# systemctl restart apache2
```

